<?php
/**
 * Controller to fetch information about articles
 *
 * @author Federico "Lox" Lucignano <federico@wikia-inc.com>
 */

class ArticlesApiController extends WikiaApiController {
	const ITEMS_PER_BATCH = 25;
	const CACHE_VERSION = 6;
	const CLIENT_CACHE_VALIDITY = 86400;//24h
	const MAX_ITEMS = 250;

	static function onArticleUpdateCategoryCounts( $this, $added, $deleted ) {
		foreach ( $added + $deleted as $cat) {
			WikiaDataAccess::cachePurge( self::getCategoryCacheKey( $cat ) );
		}

		return true;
	}

	/**
	 * @param $category
	 * @return array|null|string
	 */
	private function getCategoryMembers( $category ){
		return WikiaDataAccess::cache(
			self::getCategoryCacheKey( $category ),
			self::CLIENT_CACHE_VALIDITY,
			function() use ( $category ) {
				$ids = ApiService::call(
					array(
						'action' => 'query',
						'list' => 'categorymembers',
						'cmprop' => 'ids',
						'cmsort' => 'timestamp',
						'cmdir' => 'desc',
						'cmtitle' => 'Category:' . $category,
						'cmlimit' => 5000
					)
				);

				if ( !empty( $ids ) ) {
					$ids = $ids['query']['categorymembers'];

					foreach( $ids as &$id ) {
						$id = $id['pageid'];
					}
				}

				return $ids;
			}
			//,WikiaDataAccess::REFRESH_CACHE
			//,WikiaDataAccess::SKIP_CACHE
		);
	}

	/**
	 * Get the top articles by pageviews optionally filtering by vertical namespace
	 *
	 * @requestParam string $namespaces [OPTIONAL] The name of the namespaces (e.g. Main, Category, File, etc.) to use as a filter, comma separated
	 * @requestParam integer $limit [OPTIONAL] The maximum number of results to fetch, defaults to 25
	 * @requestParam integer $batch [OPTIONAL] The batch/page index to retrieve, defaults to 1
	 *
	 * @responseParam array $items The list of top articles by pageviews matching the optional filtering
	 * @responseParam integer $total The total number of results
	 * @responseParam integer $currentBatch The index of the current batch/page
	 * @responseParam integer $batches The total number of batches/pages
	 * @responseParam integer $next The amount of items in the next batch/page
	 *
	 * @example http://glee.wikia.com/wikia.php?controller=ArticlesApi&method=getList&namespaces=Main,Category
	 */
	public function getList() {
		$this->wf->ProfileIn( __METHOD__ );

		$namespaces = $this->request->getVal( 'namespaces', null );
		$category = $this->request->getVal( 'category' );
		$ids = null;

		if ( !empty( $category )) {
			$cat = Title::newFromText( $category, NS_CATEGORY );

			if ( !$cat->exists() ) {
				throw new InvalidParameterApiException( 'category' );
			}

			$ids = $this->getCategoryMembers( $category );
		}

		if ( !empty( $namespaces ) ) {
			$namespaces = explode( ',', $namespaces );

			foreach ( $namespaces as &$n ) {
				$n = ( strtolower( $n ) === 'main' ) ? 0 : $this->wg->ContLang->getNsIndex( $n );
			}
		}

		$articles = DataMartService::getTopArticlesByPageview( $this->wg->CityId, $ids, $namespaces, false, self::MAX_ITEMS );
		$collection = array();

		if ( !empty( $articles ) ) {
			$ids = array();

			foreach ( array_keys( $articles ) as $i ) {
				$cache = $this->wg->Memc->get( self::getArticleCacheKey( $i ) );

				if ( !is_array( $cache ) ) {
					$ids[] = $i;
				} else {
					$collection[$i] = $cache;
				}
			}

			$articles = null;

			if ( count( $ids) > 0 ) {
				$titles = Title::newFromIDs( $ids );

				if ( !empty( $titles ) ) {
					foreach ( $titles as $t ) {
						$ns = $t->getNamespace();
						$id = $t->getArticleID();
						$collection[$id] = array(
							'title' => $t->getText(),
							'url' => $t->getFullURL(),
							'namespace' => array(
								'id' => $t->getNamespace(),
								'text' => ( $ns === 0 ) ? 'Main' : $t->getNsText()
							)
						);

						$this->wg->Memc->set( self::getArticleCacheKey( $id ), $collection[$id], 86400 );
					}
				}

				$titles = null;
			}
		}

		$this->response->setCacheValidity(
			self::CLIENT_CACHE_VALIDITY,
			self::CLIENT_CACHE_VALIDITY,
			array(
				WikiaResponse::CACHE_TARGET_BROWSER,
				WikiaResponse::CACHE_TARGET_VARNISH
			)
		);

		$this->response->setVal( 'items', $collection );

		$batches = null;
		$this->wf->ProfileOut( __METHOD__ );
	}

	/**
	 * Get details about one or more articles
	 *
	 * @requestParam string $ids A string with a comma-separated list of article ID's
	 * @requestParam integer $abstract [OPTIONAL] The desired length for the article's abstract, defaults to 100, maximum 500, 0 for no abstract
	 * @requestParam integer $width [OPTIONAL] The desired width for the thumbnail, defaults to 200, 0 for no thumbnail
	 * @requestParam integer $height [OPTIONAL] The desired height for the thumbnail, defaults to 200, 0 for no thumbnail
	 *
	 * @responseParam array A list of results with the article ID as the index, each item has a revision, namespace (id, text), comments (if ArticleComments is enabled on the wiki), abstract (if available), thumbnail (if available) property
	 *
	 * @example http://glee.wikia.com/wikia.php?controller=ArticlesApi&method=getDetails&ids=2187,23478&abstract=200&width=300&height=150
	 */
	public function getDetails() {
		$this->wf->profileIn( __METHOD__ );

		$articles = $this->request->getVal( 'ids', null );
		$abstractLen = $this->request->getInt( 'abstract', 100 );
		$width = $this->request->getInt( 'width', 200 );
		$height = $this->request->getInt( 'height', 200 );
		$collection = array();

		if ( !empty( $articles ) ) {
			$articles = explode( ',', $articles );
			$ids = array();


			foreach ( $articles as $i ) {
				$cache = $this->wg->Memc->get( self::getDetailsCacheKey( $i ) );

				if ( !is_array( $cache ) ) {
					$ids[] = $i;
				} else {
					$collection[$i] = $cache;
				}
			}

			if ( count( $ids ) > 0 ) {
				$titles = Title::newFromIDs( $ids );

				if ( !empty( $titles ) ) {
					foreach ( $titles as $t ) {
						$ns = $t->getNamespace();
						$id = $t->getArticleID();

						$collection[$id] = array(
							'revision' => $t->getLatestRevID(),
							'namespace' => array(
								'id' => $t->getNamespace(),
								'text' => ( $ns === 0 ) ? 'Main' : $t->getNsText()
							)
						);

						$collection[$id]['comments'] = ( class_exists( 'ArticleCommentList' ) ) ? ArticleCommentList::newFromTitle( $t )->getCountAllNested() : false;

						$this->wg->Memc->set( self::getDetailsCacheKey( $id ), $collection[$id], 86400 );
					}
				}

				$titles = null;
			}

			//ImageServing has separate caching
			//so processing it separately allows to
			//make the thumbnail's size parametrical without
			//invalidating the titles details' cache
			//or the need to duplicate it
			if ( $width > 0 && $height > 0 ) {
				$is = new ImageServing( $articles, $width, $height );
				$thumbnails = $is->getImages( 1 );
			} else {
				$thumbnails = array();
			}

			$articles = null;

			//ArticleService has separate caching
			//so processing it separately allows to
			//make the length parametrical without
			//invalidating the titles details' cache
			//or the need to duplicate it
			foreach ( $collection as $id => &$details ) {
				if ( $abstractLen > 0 ) {
					$as = new ArticleService( $id );
					$snippet = $as->getTextSnippet( $abstractLen );
				} else {
					$snippet = null;
				}

				$details['abstract'] = $snippet;
				$details['thumbnail'] = ( array_key_exists( $id, $thumbnails ) ) ? $thumbnails[$id][0]['url'] : null;
			}

			$thumbnails = null;
		}

		/*
		 * Varnish/Browser caching not appliable for
		 * for this method's data to be kept up-to-date
		 */

		$this->response->setVal( 'items', $collection );

		$collection = null;
		$this->wf->ProfileOut( __METHOD__ );
	}

	static private function getArticleCacheKey( $id ) {
		return F::app()->wf->MemcKey( __CLASS__, self::CACHE_VERSION, 'article', $id );
	}

	static private function getDetailsCacheKey( $id ) {
		return F::app()->wf->MemcKey( __CLASS__, self::CACHE_VERSION, 'details', $id );
	}

	static private function getCategoryCacheKey( $category ) {
		return F::app()->wf->MemcKey( __CLASS__, self::CACHE_VERSION, 'category', $category );
	}

	static public function purgeCache( $id ) {
		$memc = F::app()->wg->Memc;
		$memc->delete( self::getArticleCacheKey( $id ) );
		$memc->delete( self::getDetailsCacheKey( $id ) );
	}
}