<?php

class AllinfoboxesQueryPage extends PageQueryPage {

	const LIMIT = 1000;
	const ALL_INFOBOXES_TYPE = 'AllInfoboxes';

	function __construct() {
		parent::__construct( self::ALL_INFOBOXES_TYPE );
	}

	public function isListed() {
		return false;
	}

	public function sortDescending() {
		return true;
	}

	public function isExpensive() {
		return true;
	}

	/**
	 * A wrapper for calling the querycache table
	 *
	 * @param bool $offset
	 * @param int $limit
	 *
	 * @return ResultWrapper
	 */
	public function doQuery( $offset = false, $limit = self::LIMIT ) {
		return $this->fetchFromCache( $limit, $offset );
	}

	/**
	 * Update the querycache table
	 *
	 * @param bool $limit Only for consistency
	 * @param bool $ignoreErrors Only for consistency
	 *
	 * @return bool|int
	 */
	public function recache( $limit = false, $ignoreErrors = true ) {
		$dbw = wfGetDB( DB_MASTER );

		$infoboxes = $this->reallyDoQuery();
		$dbw->begin();

		( new WikiaSQL() )
			->DELETE( 'querycache' )
			->WHERE( 'qc_type' )->EQUAL_TO( $this->getName() )
			->run( $dbw );

		$inserted = 0;
		if ( !empty( $infoboxes ) ) {
			( new WikiaSQL() )
				->INSERT()->INTO( 'querycache', [
					'qc_type',
					'qc_value',
					'qc_namespace',
					'qc_title'
				] )
				->VALUES( $infoboxes )
				->run( $dbw );

			$inserted = $dbw->affectedRows();
			if ( $inserted === 0 ) {
				$dbw->rollback();

				return false;
			}
			$dbw->commit();
		}

		return $inserted;
	}

	/**
	 * Queries all templates and get only those with portable infoboxes
	 *
	 * @param bool $limit Only for consistency
	 * @param bool $offset Only for consistency
	 *
	 * @return bool|mixed
	 */
	public function reallyDoQuery( $limit = false, $offset = false ) {
		$dbr = wfGetDB( DB_SLAVE, [ $this->getName(), __METHOD__, 'vslow' ] );
		$result = ( new WikiaSQL() )
			->SELECT( 'page_id', 'page_title', 'page_namespace' )
			->FROM( 'page' )
			->WHERE( 'page_namespace' )->EQUAL_TO( NS_TEMPLATE )
			->AND_( 'page_is_redirect' )->EQUAL_TO( 0 )
			->run( $dbr, function ( ResultWrapper $result ) {
				$out = [ ];
				while ( $row = $result->fetchRow() ) {
					$out[] = [ 'type' => $this->getName(),
							   'pageid' => $row[ 'page_id' ],
							   'ns' => $row[ 'page_namespace' ],
							   'title' => $row[ 'page_title' ] ];
				}

				return $out;
			} );

		return array_filter( $result, function ( $tmpl ) {
			$data = PortableInfoboxDataService::newFromPageID( $tmpl[ 'pageid' ] )->getData();

			return !empty( $data );
		} );
	}
}