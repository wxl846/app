<?php
/**
 * Configuration settings introduced by Wikia.
 *
 * Any new variables should be declared here. Since this file is used during
 * installation, the defaults should work on any installation (including local
 * installs).
 *
 * If you want to change their value, edit LocalSettings.php to make a change
 * for this specific installation. To override values for production,
 * edit /wikia-conf/CommonSettings.php
 */

# This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
if( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki and is not a valid entry point\n";
	die( 1 );
}

/**
 * @name $wgCityId
 *
 * contains wiki identifier from city_list table. If wiki is not from wiki.factory
 * contains null!
 */
$wgCityId = null;

/**
 * replace ExternalStoreDB with our version for other clusters than main
 */
$wgUseFakeExternalStoreDB = false;

/**
 * includes common for all wikis
 */
require_once ( $IP."/includes/wikia/Defines.php" );
require_once ( $IP."/includes/wikia/GlobalFunctions.php" );
require_once ( $IP."/includes/wikia/Wikia.php" );
require_once ( $IP."/includes/wikia/WikiaMailer.php" );
require_once ( $IP."/extensions/GlobalMessages/GlobalMessages.setup.php" );
require_once ( $IP."/extensions/Math/Math.php" );

/**
 * Add composer dependencies before proceeding to lib/Wikia. For now, we are committing
 * dependencies added via composer to lib/composer until external dependencies with composer/packagist
 * can be eliminated.
 */
require_once("$IP/lib/composer/autoload.php");
// configure FluentSQL to use the extended WikiaSQL class
FluentSql\StaticSQL::setClass("\\WikiaSQL");

global $wgDBname;
if($wgDBname != 'uncyclo') {
	include_once( "$IP/extensions/wikia/SkinChooser/SkinChooser.php" );
}

/**
 * autoload classes
 */
global $wgAutoloadClasses;

/**
 * Nirvana framework classes
 */
$wgAutoloadClasses['F'] = $IP . '/includes/wikia/nirvana/WikiaApp.class.php';
$wgAutoloadClasses['WikiaApp'] = $IP . '/includes/wikia/nirvana/WikiaApp.class.php';
$wgAutoloadClasses['WikiaObject'] = $IP . '/includes/wikia/nirvana/WikiaObject.class.php';
$wgAutoloadClasses['WikiaRegistry'] = $IP . '/includes/wikia/nirvana/WikiaRegistry.class.php';
$wgAutoloadClasses['WikiaGlobalRegistry'] = $IP . '/includes/wikia/nirvana/WikiaGlobalRegistry.class.php';
$wgAutoloadClasses['WikiaLocalRegistry'] = $IP . '/includes/wikia/nirvana/WikiaLocalRegistry.class.php';
$wgAutoloadClasses['WikiaDispatcher'] = $IP . '/includes/wikia/nirvana/WikiaDispatcher.class.php';
$wgAutoloadClasses['WikiaDispatchableObject'] = $IP . '/includes/wikia/nirvana/WikiaDispatchableObject.class.php';
$wgAutoloadClasses['WikiaController'] = $IP . '/includes/wikia/nirvana/WikiaController.class.php';
$wgAutoloadClasses['WikiaParserTagController'] = $IP . '/includes/wikia/nirvana/WikiaParserTagController.class.php';
$wgAutoloadClasses['WikiaService'] = $IP . '/includes/wikia/nirvana/WikiaService.class.php';
$wgAutoloadClasses['WikiaModel'] = $IP . '/includes/wikia/nirvana/WikiaModel.class.php';
$wgAutoloadClasses['WikiaSpecialPageController'] = $IP . '/includes/wikia/nirvana/WikiaSpecialPageController.class.php';
$wgAutoloadClasses['WikiaErrorController'] = $IP . '/includes/wikia/nirvana/WikiaErrorController.class.php';
$wgAutoloadClasses['WikiaRequest'] = $IP . '/includes/wikia/nirvana/WikiaRequest.class.php';
$wgAutoloadClasses['WikiaResponse'] = $IP . '/includes/wikia/nirvana/WikiaResponse.class.php';
$wgAutoloadClasses['WikiaView'] = $IP . '/includes/wikia/nirvana/WikiaView.class.php';
$wgAutoloadClasses['WikiaSkin'] = $IP . '/includes/wikia/nirvana/WikiaSkin.class.php';
$wgAutoloadClasses['WikiaSkinTemplate'] = $IP . '/includes/wikia/nirvana/WikiaSkinTemplate.class.php';
$wgAutoloadClasses['WikiaAccessRules'] = $IP . '/includes/wikia/nirvana/WikiaAccessRules.class.php';

/**
 * Exceptions
 */
$wgAutoloadClasses['WikiaException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['WikiaDispatchedException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['WikiaHttpException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['BadRequestException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['ForbiddenException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['NotFoundException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['MethodNotAllowedException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['NotImplementedException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['ControllerNotFoundException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['MethodNotFoundException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";
$wgAutoloadClasses['PermissionsException'] = "{$IP}/includes/wikia/nirvana/WikiaException.php";


$wgAutoloadClasses['AssetsManager'] = $IP . '/extensions/wikia/AssetsManager/AssetsManager.class.php';
$wgAutoloadClasses['AssetsConfig'] = $IP . '/extensions/wikia/AssetsManager/AssetsConfig.class.php';

/**
 * Wikia API
 * (based on Nirvana)
 */

//holds a list of all the registered API controllers
//@see WikiaApp::registerApiController
$wgWikiaAPIControllers = array();

//Wikia API Hooks
$wgAutoloadClasses[ 'ApiHooks'] =  "{$IP}/includes/wikia/api/ApiHooks.class.php" ;

$wgHooks['WikiFactoryChanged'][] = 'ApiHooks::onWikiFactoryChanged';
$wgHooks['MessageCacheReplace'][] = 'ApiHooks::onMessageCacheReplace';
$wgHooks['ArticleDeleteComplete'][] = 'ApiHooks::onArticleDeleteComplete';
$wgHooks['ArticleSaveComplete'][] = 'ApiHooks::onArticleSaveComplete';
$wgHooks['ArticleRollbackComplete'][] = 'ApiHooks::onArticleRollbackComplete';
$wgHooks['TitleMoveComplete'][] = 'ApiHooks::onTitleMoveComplete';
$wgHooks['ArticleCommentListPurgeComplete'][] = 'ApiHooks::ArticleCommentListPurgeComplete';


//Wikia API base controller, all the others extend this class
$wgAutoloadClasses['WikiaApiController'] =  "{$IP}/includes/wikia/api/WikiaApiController.class.php" ;

//Wikia API controllers
$wgAutoloadClasses['DiscoverApiController'] = "{$IP}/includes/wikia/api/DiscoverApiController.class.php";
$wgAutoloadClasses['DesignSystemApiController'] = "{$IP}/includes/wikia/api/DesignSystemApiController.class.php";
$wgAutoloadClasses['NavigationApiController'] = "{$IP}/includes/wikia/api/NavigationApiController.class.php";
$wgAutoloadClasses['ArticlesApiController'] = "{$IP}/includes/wikia/api/ArticlesApiController.class.php";
$wgAutoloadClasses['SearchSuggestionsApiController'] = "{$IP}/includes/wikia/api/SearchSuggestionsApiController.class.php";
$wgAutoloadClasses['StatsApiController'] = "{$IP}/includes/wikia/api/StatsApiController.class.php";
$wgAutoloadClasses['RelatedPagesApiController'] = "{$IP}/includes/wikia/api/RelatedPagesApiController.class.php";
$wgAutoloadClasses['ActivityApiController'] = "{$IP}/includes/wikia/api/ActivityApiController.class.php";
$wgAutoloadClasses['UserApiController'] = "{$IP}/includes/wikia/api/UserApiController.class.php";
$wgAutoloadClasses['MoviesApiController'] = "{$IP}/includes/wikia/api/MoviesApiController.class.php";
$wgAutoloadClasses['DWDimensionApiController'] = "{$IP}/includes/wikia/api/DWDimensionApiController.class.php";
$wgAutoloadClasses['DWDimensionApiControllerSQL'] = "{$IP}/includes/wikia/api/DWDimensionApiControllerSQL.class.php";
$wgAutoloadClasses['InfoboxApiController'] = "{$IP}/includes/wikia/api/InfoboxApiController.class.php";
$wgAutoloadClasses['TemplateClassificationApiController'] = "{$IP}/includes/wikia/api/TemplateClassificationApiController.class.php";
$wgExtensionMessagesFiles['WikiaApi'] = "{$IP}/includes/wikia/api/WikiaApi.i18n.php";

$wgWikiaApiControllers['DiscoverApiController'] = "{$IP}/includes/wikia/api/DiscoverApiController.class.php";
$wgWikiaApiControllers['DesignSystemApiController'] = "{$IP}/includes/wikia/api/DesignSystemApiController.class.php";
$wgWikiaApiControllers['NavigationApiController'] = "{$IP}/includes/wikia/api/NavigationApiController.class.php";
$wgWikiaApiControllers['ArticlesApiController'] = "{$IP}/includes/wikia/api/ArticlesApiController.class.php";
$wgWikiaApiControllers['SearchSuggestionsApiController'] = "{$IP}/includes/wikia/api/SearchSuggestionsApiController.class.php";
$wgWikiaApiControllers['StatsApiController'] = "{$IP}/includes/wikia/api/StatsApiController.class.php";
$wgWikiaApiControllers['RelatedPagesApiController'] = "{$IP}/includes/wikia/api/RelatedPagesApiController.class.php";
$wgWikiaApiControllers['ActivityApiController'] = "{$IP}/includes/wikia/api/ActivityApiController.class.php";
$wgWikiaApiControllers['UserApiController'] = "{$IP}/includes/wikia/api/UserApiController.class.php";
$wgWikiaApiControllers['MoviesApiController'] = "{$IP}/includes/wikia/api/MoviesApiController.class.php";
$wgWikiaApiControllers['DWDimensionApiController'] = "{$IP}/includes/wikia/api/DWDimensionApiController.class.php";
$wgWikiaApiControllers['InfoboxApiController'] = "{$IP}/includes/wikia/api/InfoboxApiController.class.php";
$wgWikiaApiControllers['LogEventsApiController'] = "{$IP}/includes/wikia/api/LogEventsApiController.class.php";

//Wikia Api exceptions classes
$wgAutoloadClasses[ 'ApiAccessService' ] = "{$IP}/includes/wikia/api/services/ApiAccessService.php";
$wgAutoloadClasses[ 'ApiOutboundingLinksService' ] = "{$IP}/includes/wikia/api/services/ApiOutboundingLinksService.php";
$wgAutoloadClasses[ 'BadRequestApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'OutOfRangeApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'MissingParameterApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'InvalidParameterApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'InvalidDataApiException' ] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'LimitExceededApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";
$wgAutoloadClasses[ 'NotFoundApiException'] = "{$IP}/includes/wikia/api/ApiExceptions.php";

/**
 * Wikia API end
 */

/**
 * Wikia Skins
 *
 * this need to be autoloaded to avoid PHPUnit replacing the classes definition with mocks
 * and brake the world; Monobook is already autoloaded in /includes/DefaultSettings.php
 */
$wgAutoloadClasses[ 'SkinOasis'] =  "{$IP}/skins/Oasis.php" ;
$wgAutoloadClasses[ 'SkinWikiaMobile'] =  "{$IP}/skins/WikiaMobile.php" ;

$wgAutoloadClasses['SpamBlacklist'] = $IP . '/extensions/SpamBlacklist/SpamBlacklist_body.php';
$wgAutoloadClasses['BaseBlacklist'] = $IP . '/extensions/SpamBlacklist/BaseBlacklist.php';
$wgAutoloadClasses['SpamRegexBatch'] = $IP . '/extensions/SpamBlacklist/SpamRegexBatch.php';
$wgAutoloadClasses['WikiaSpamRegexBatch'] = $IP . '/extensions/wikia/WikiaSpamRegexBatch/WikiaSpamRegexBatch.php';

/**
 * Wikia Templating System
 */
$wgAutoloadClasses[ 'Wikia\Template\Engine' ] = "{$IP}/includes/wikia/template/Engine.class.php";
$wgAutoloadClasses[ 'Wikia\Template\PHPEngine' ] = "{$IP}/includes/wikia/template/PHPEngine.class.php";
$wgAutoloadClasses[ 'Wikia\Template\MustacheEngine' ] = "{$IP}/includes/wikia/template/MustacheEngine.class.php";
//deprecated, will be removed
$wgAutoloadClasses[ 'EasyTemplate' ] = "{$IP}/includes/wikia/EasyTemplate.php";

/**
 * Custom wikia classes
 */
$wgAutoloadClasses[ "ArticleQualityService"           ] = "$IP/includes/wikia/services/ArticleQualityService.php";
$wgAutoloadClasses[ "GlobalTitle"                     ] = "$IP/includes/wikia/GlobalTitle.php";
$wgAutoloadClasses[ "GlobalFile"                      ] = "$IP/includes/wikia/GlobalFile.class.php";
$wgAutoloadClasses[ "WikiFactory"                     ] = "$IP/extensions/wikia/WikiFactory/WikiFactory.php";
$wgAutoloadClasses[ "WikiFactoryHub"                  ] = "$IP/extensions/wikia/WikiFactory/Hubs/WikiFactoryHub.php";
$wgAutoloadClasses[ "WikiFactoryHubHooks"             ] = "$IP/extensions/wikia/WikiFactory/Hubs/WikiFactoryHubHooks.class.php";
$wgAutoloadClasses[ 'FakeLocalFile'                   ] = "$IP/includes/wikia/FakeLocalFile.class.php";
$wgAutoloadClasses[ 'WikiaUploadStash'                ] = "$IP/includes/wikia/upload/WikiaUploadStash.class.php";
$wgAutoloadClasses[ 'WikiaUploadStashFile'            ] = "$IP/includes/wikia/upload/WikiaUploadStashFile.class.php";
$wgAutoloadClasses[ 'WikiaPageType'                   ] = "$IP/includes/wikia/WikiaPageType.class.php";
$wgAutoloadClasses[ 'WikiaSkinMonoBook'               ] = "$IP/skins/wikia/WikiaMonoBook.php";
$wgAutoloadClasses[ 'PaginationController'            ] = "$IP/includes/wikia/services/PaginationController.class.php";
$wgAutoloadClasses[ 'MemcacheSync'                    ] = "$IP/includes/wikia/MemcacheSync.class.php";
$wgAutoloadClasses[ 'LibmemcachedBagOStuff'           ] = "$IP/includes/cache/wikia/LibmemcachedBagOStuff.php";
$wgAutoloadClasses[ 'WikiaAssets'                     ] = "$IP/includes/wikia/WikiaAssets.class.php";
$wgAutoloadClasses[ 'FakeSkin'                        ] = "$IP/includes/wikia/FakeSkin.class.php";
$wgAutoloadClasses[ 'WikiaUpdater'                    ] = "$IP/includes/wikia/WikiaUpdater.php";
$wgHooks          [ 'LoadExtensionSchemaUpdates'      ][] = 'WikiaUpdater::update';
$wgAutoloadClasses[ 'WikiaDataAccess'                 ] = "$IP/includes/wikia/WikiaDataAccess.class.php";
$wgAutoloadClasses[ 'WikiaUserPropertiesController'   ] = "$IP/includes/wikia/WikiaUserPropertiesController.class.php";
$wgAutoloadClasses[ 'TitleBatch'                      ] = "$IP/includes/wikia/cache/TitleBatch.php";
$wgAutoloadClasses[ 'WikiaUserPropertiesHandlerBase'  ] = "$IP/includes/wikia/models/WikiaUserPropertiesHandlerBase.class.php";
$wgAutoloadClasses[ 'ParserPool'                      ] = "$IP/includes/wikia/parser/ParserPool.class.php";
$wgAutoloadClasses[ 'WikiDataSource'                  ] = "$IP/includes/wikia/WikiDataSource.php";
$wgAutoloadClasses[ 'CurlMultiClient'                 ] = "$IP/includes/wikia/CurlMultiClient.php";
$wgAutoloadClasses[ 'DateFormatHelper'                ] = "$IP/includes/wikia/DateFormatHelper.php";
$wgAutoloadClasses[ 'CategoryHelper'                  ] = "$IP/includes/wikia/helpers/CategoryHelper.class.php";
$wgAutoloadClasses[ 'WikiaTagBuilderHelper'           ] = "$IP/includes/wikia/helpers/WikiaTagBuilderHelper.class.php";
$wgAutoloadClasses[ 'WikiaIFrameTagBuilderHelper'     ] = "$IP/includes/wikia/helpers/WikiaIFrameTagBuilderHelper.class.php";
$wgAutoloadClasses[ 'Wikia\\Measurements\\Driver'     ] = "$IP/includes/wikia/measurements/Drivers.php";
$wgAutoloadClasses[ 'Wikia\\Measurements\\Drivers'    ] = "$IP/includes/wikia/measurements/Drivers.php";
$wgAutoloadClasses[ 'Wikia\\Measurements\\NewrelicDriver' ] = "$IP/includes/wikia/measurements/Drivers.php";
$wgAutoloadClasses[ 'Wikia\\Measurements\\DummyDriver'    ] = "$IP/includes/wikia/measurements/Drivers.php";
$wgAutoloadClasses[ 'Wikia\\Measurements\\Time'       ] = "$IP/includes/wikia/measurements/Time.class.php";
$wgAutoloadClasses[ 'Wikia\\SwiftStorage'             ] = "$IP/includes/wikia/SwiftStorage.class.php";
$wgAutoloadClasses[ 'WikiaSQL'                        ] = "$IP/includes/wikia/WikiaSQL.class.php";
$wgAutoloadClasses[ 'WikiaSQLCache'                   ] = "$IP/includes/wikia/WikiaSQLCache.class.php";
$wgAutoloadClasses[ 'WikiaSanitizer'                  ] = "$IP/includes/wikia/WikiaSanitizer.class.php";
$wgAutoloadClasses[ 'CeleryPurge'                     ] = "$IP/includes/cache/wikia/CeleryPurge.class.php";
$wgAutoloadClasses[ 'Transaction'                     ] = "$IP/includes/wikia/transaction/Transaction.php";
$wgAutoloadClasses[ 'TransactionTrace'                ] = "$IP/includes/wikia/transaction/TransactionTrace.php";
$wgAutoloadClasses[ 'TransactionClassifier'           ] = "$IP/includes/wikia/transaction/TransactionClassifier.php";
$wgAutoloadClasses[ 'TransactionTraceNewrelic'        ] = "$IP/includes/wikia/transaction/TransactionTraceNewrelic.php";
$wgHooks          [ 'ArticleViewAddParserOutput'      ][] = 'Transaction::onArticleViewAddParserOutput';
$wgHooks          [ 'AfterSmwfGetStore'               ][] = 'Transaction::onAfterSmwfGetStore';
$wgHooks          [ 'RestInPeace'                     ][] = 'Transaction::onRestInPeace';
$wgHooks          [ 'RestInPeace'                     ][] = 'CeleryPurge::onRestInPeace';
$wgAutoloadClasses[ 'Wikia\\Blogs\\BlogTask'          ] = "$IP/extensions/wikia/Blogs/BlogTask.class.php";
$wgAutoloadClasses[ 'FileNamespaceSanitizeHelper'     ] = "$IP/includes/wikia/helpers/FileNamespaceSanitizeHelper.php";
$wgAutoloadClasses[ 'TemplatePageHelper'              ] = "$IP/includes/wikia/helpers/TemplatePageHelper.php";
$wgAutoloadClasses[ 'HtmlHelper'                      ] = "$IP/includes/wikia/helpers/HtmlHelper.class.php";
$wgAutoloadClasses[ 'CrossOriginResourceSharingHeaderHelper' ] = "$IP/includes/wikia/helpers/CrossOriginResourceSharingHeaderHelper.php";
$wgAutoloadClasses[ 'VignetteRequest'                 ] = "{$IP}/includes/wikia/vignette/VignetteRequest.php";
$wgAutoloadClasses[ 'UrlGeneratorInterface'           ] = "{$IP}/includes/wikia/vignette/UrlGeneratorInterface.php";
$wgAutoloadClasses[ 'VignetteUrlToUrlGenerator'       ] = "{$IP}/includes/wikia/vignette/VignetteUrlToUrlGenerator.php";
$wgAutoloadClasses['Swagger'] = "$IP/includes/wikia/swagger/Swagger.php";
$wgAutoloadClasses['SwaggerResource'] = "$IP/includes/wikia/swagger/SwaggerResource.php";
$wgAutoloadClasses['SwaggerApi'] = "$IP/includes/wikia/swagger/SwaggerApi.php";
$wgAutoloadClasses['SwaggerOperation'] = "$IP/includes/wikia/swagger/SwaggerOperation.php";
$wgAutoloadClasses['SwaggerParameter'] = "$IP/includes/wikia/swagger/SwaggerParameter.php";
$wgAutoloadClasses['SwaggerModel'] = "$IP/includes/wikia/swagger/SwaggerModel.php";
$wgAutoloadClasses['SwaggerModelProperty'] = "$IP/includes/wikia/swagger/SwaggerModelProperty.php";
$wgAutoloadClasses['SwaggerErrorResponse'] = "$IP/includes/wikia/swagger/SwaggerErrorResponse.php";
$wgAutoloadClasses['TemplateDataExtractor'] = "$IP/includes/wikia/TemplateDataExtractor.class.php";
$wgAutoloadClasses['WikiaHtmlTitle'] = "$IP/includes/wikia/WikiaHtmlTitle.class.php";

/**
 * Resource Loader enhancements
 */
$wgAutoloadClasses[ 'ResourceLoaderGlobalWikiModule'  ]  = "$IP/includes/wikia/resourceloader/ResourceLoaderGlobalWikiModule.class.php";
$wgAutoloadClasses[ 'ResourceLoaderCustomWikiModule'  ]  = "$IP/includes/wikia/resourceloader/ResourceLoaderCustomWikiModule.class.php";
$wgAutoloadClasses[ 'ResourceLoaderHooks'  ]             = "$IP/includes/wikia/resourceloader/ResourceLoaderHooks.class.php";
$wgHooks['ResourceLoaderRegisterModules'][]              = "ResourceLoaderHooks::onResourceLoaderRegisterModules";
$wgHooks['ResourceLoaderUserOptionsModuleGetOptions'][]  = "ResourceLoaderHooks::onResourceLoaderUserOptionsModuleGetOptions";
$wgHooks['ResourceLoaderFileModuleConcatenateScripts'][] = 'ResourceLoaderHooks::onResourceLoaderFileModuleConcatenateScripts';
$wgHooks['ResourceLoaderSiteModule::getPages'][]         = 'ResourceLoaderHooks::onResourceLoaderSiteModuleGetPages';
$wgHooks['ResourceLoaderUserModule::getPages'][]         = 'ResourceLoaderHooks::onResourceLoaderUserModuleGetPages';
$wgHooks['ResourceLoaderCacheControlHeaders'][]          = "ResourceLoaderHooks::onResourceLoaderCacheControlHeaders";
$wgHooks['AlternateResourceLoaderURL'][]                 = "ResourceLoaderHooks::onAlternateResourceLoaderURL";
$wgHooks['ResourceLoaderMakeQuery'][]                    = "ResourceLoaderHooks::onResourceLoaderMakeQuery";
$wgHooks['ResourceLoaderModifyMaxAge'][]                 = "ResourceLoaderHooks::onResourceLoaderModifyMaxAge";

// services
$wgAutoloadClasses['ApiService']  =  $IP.'/includes/wikia/services/ApiService.class.php';
$wgAutoloadClasses['ArticleService'] = $IP.'/includes/wikia/services/ArticleService.class.php';
$wgAutoloadClasses['AvatarService'] = $IP.'/includes/wikia/services/AvatarService.class.php';
$wgAutoloadClasses['MediaQueryService'] = $IP.'/includes/wikia/services/MediaQueryService.class.php';
$wgAutoloadClasses['PageStatsService']  =  $IP.'/includes/wikia/services/PageStatsService.class.php';
$wgAutoloadClasses['UserStatsService'] = $IP.'/includes/wikia/services/UserStatsService.class.php';
$wgAutoloadClasses['CategoriesService'] = $IP.'/includes/wikia/services/CategoriesService.class.php';
$wgAutoloadClasses['UserCommandsService'] = $IP.'/includes/wikia/services/UserCommandsService.class.php';
$wgAutoloadClasses['ToolbarService'] = $IP.'/includes/wikia/services/ToolbarService.class.php';
$wgAutoloadClasses['SharedToolbarService'] = $IP.'/includes/wikia/services/SharedToolbarService.class.php';
$wgAutoloadClasses['CsvService'] = $IP . '/includes/wikia/services/CsvService.class.php';
$wgAutoloadClasses['MobileService'] = $IP . '/includes/wikia/services/MobileService.class.php';
$wgAutoloadClasses['TemplateService'] = $IP . '/includes/wikia/services/TemplateService.class.php';
$wgAutoloadClasses['SpriteService'] = $IP . '/includes/wikia/services/SpriteService.class.php';
$wgAutoloadClasses['SocialSharingService'] = $IP . '/includes/wikia/services/SocialSharingService.class.php';
$wgAutoloadClasses['HubService'] = $IP . '/includes/wikia/services/HubService.class.php';
$wgAutoloadClasses['ImagesService'] = $IP . '/includes/wikia/services/ImagesService.class.php';
$wgAutoloadClasses['WikiDetailsService'] = $IP . '/includes/wikia/services/WikiDetailsService.class.php';
$wgAutoloadClasses['WikiService'] = $IP . '/includes/wikia/services/WikiService.class.php';
$wgAutoloadClasses['DataMartService'] = $IP . '/includes/wikia/services/DataMartService.class.php';
$wgAutoloadClasses['WAMService'] = $IP . '/includes/wikia/services/WAMService.class.php';
$wgAutoloadClasses['VideoService'] = $IP . '/includes/wikia/services/VideoService.class.php';
$wgAutoloadClasses['UserService']  =  $IP.'/includes/wikia/services/UserService.class.php';
$wgAutoloadClasses['MustacheService'] = $IP . '/includes/wikia/services/MustacheService.class.php';
$wgAutoloadClasses['RevisionService'] = $IP . '/includes/wikia/services/RevisionService.class.php';
$wgAutoloadClasses['InfoboxesService'] = $IP . '/includes/wikia/services/InfoboxesService.class.php';
$wgAutoloadClasses['RenderContentOnlyHelper'] = $IP . '/includes/wikia/RenderContentOnlyHelper.class.php';
$wgAutoloadClasses['SolrDocumentService'] = $IP . '/includes/wikia/services/SolrDocumentService.class.php';
$wgAutoloadClasses['LicensedWikisService']  =  $IP.'/includes/wikia/services/LicensedWikisService.class.php';
$wgAutoloadClasses['ArticleQualityService'] = $IP.'/includes/wikia/services/ArticleQualityService.php';
$wgAutoloadClasses['PortableInfoboxDataService'] = $IP . '/extensions/wikia/PortableInfobox/services/PortableInfoboxDataService.class.php';
$wgAutoloadClasses['PortableInfoboxBuilderService'] = $IP . '/extensions/wikia/PortableInfoboxBuilder/services/PortableInfoboxBuilderService.class.php';
$wgAutoloadClasses['PortableInfoboxBuilderHelper'] = $IP . '/extensions/wikia/PortableInfoboxBuilder/services/PortableInfoboxBuilderHelper.class.php';
$wgAutoloadClasses['TemplateClassificationService'] = $IP . '/includes/wikia/services/TemplateClassificationService.class.php';
$wgAutoloadClasses['CommunityDataService'] = $IP . '/includes/wikia/services/CommunityDataService.class.php';
$wgAutoloadClasses['SiteAttributeService'] = $IP . '/includes/wikia/services/SiteAttributeService.class.php';
$wgAutoloadClasses['ImageReviewService'] = $IP . '/includes/wikia/services/ImageReviewService.class.php';
$wgAutoloadClasses['LiftigniterMetadataService'] = $IP . '/includes/wikia/services/LiftigniterMetadataService.class.php';
$wgAutoloadClasses['ArticleVideoService'] = $IP . '/includes/wikia/services/ArticleVideoService.class.php';
$wgAutoloadClasses['RedirectService'] = $IP . '/includes/wikia/services/RedirectService.class.php';

// services hooks
$wgHooks['ArticleEditUpdates'][] = 'MediaQueryService::onArticleEditUpdates';
$wgHooks['ArticlePurge'][] = 'ArticleService::onArticlePurge';
$wgHooks['ArticleSaveComplete'][] = 'ArticleService::onArticleSaveComplete';
$wgHooks['ArticleDeleteComplete'][] = 'PageStatsService::onArticleDeleteComplete';
$wgHooks['ArticleSaveComplete'][] = 'PageStatsService::onArticleSaveComplete';

// controllers
$wgAutoloadClasses['Wikia\Helios\HelperController'] = "{$IP}/includes/wikia/controllers/HeliosHelperController.class.php";

// data models
$wgAutoloadClasses['WikisModel'] = "{$IP}/includes/wikia/models/WikisModel.class.php";
$wgAutoloadClasses['NavigationModel'] = "{$IP}/includes/wikia/models/NavigationModel.class.php";
$wgAutoloadClasses['WikiaCorporateModel'] = "{$IP}/includes/wikia/models/WikiaCorporateModel.class.php";
$wgAutoloadClasses['MySQLKeyValueModel'] = "{$IP}/includes/wikia/models/MySQLKeyValueModel.class.php";
$wgAutoloadClasses['DesignSystemCommunityHeaderModel'] = "{$IP}/includes/wikia/models/DesignSystemCommunityHeaderModel.class.php";
$wgAutoloadClasses['DesignSystemGlobalFooterModel'] = "{$IP}/includes/wikia/models/DesignSystemGlobalFooterModel.class.php";
$wgAutoloadClasses['DesignSystemGlobalNavigationModel'] = "{$IP}/includes/wikia/models/DesignSystemGlobalNavigationModel.class.php";
$wgAutoloadClasses['DesignSystemSharedLinks'] = "{$IP}/includes/wikia/models/DesignSystemSharedLinks.class.php";
$wgAutoloadClasses['UserRegistrationInfo'] = "$IP/includes/wikia/models/UserRegistrationInfo.php";
$wgAutoloadClasses['PromoImage'] = "{$IP}/includes/wikia/models/PromoImage.class.php";

// modules
$wgAutoloadClasses['OasisController'] = $IP.'/skins/oasis/modules/OasisController.class.php';
$wgAutoloadClasses['BodyController'] = $IP.'/skins/oasis/modules/BodyController.class.php';
$wgAutoloadClasses['BodyContentOnlyController'] = $IP.'/skins/oasis/modules/BodyContentOnlyController.class.php';
$wgAutoloadClasses['ContentDisplayController'] = $IP.'/skins/oasis/modules/ContentDisplayController.class.php';
$wgAutoloadClasses['SearchController'] = $IP.'/skins/oasis/modules/SearchController.class.php';
$wgAutoloadClasses['LatestActivityController'] = $IP.'/skins/oasis/modules/LatestActivityController.class.php';
$wgAutoloadClasses['FooterController'] = $IP.'/skins/oasis/modules/FooterController.class.php';
$wgAutoloadClasses['ArticleCategoriesController'] = $IP.'/skins/oasis/modules/ArticleCategoriesController.class.php';
$wgAutoloadClasses['AchievementsController'] = $IP.'/skins/oasis/modules/AchievementsController.class.php';
$wgAutoloadClasses['AdController'] = $IP.'/skins/oasis/modules/AdController.class.php';
$wgAutoloadClasses['AdEmptyContainerController'] = $IP.'/skins/oasis/modules/AdEmptyContainerController.class.php';
$wgAutoloadClasses['FollowedPagesController'] = $IP.'/skins/oasis/modules/FollowedPagesController.class.php';
$wgAutoloadClasses['MyToolsController'] = $IP.'/skins/oasis/modules/MyToolsController.class.php';
$wgAutoloadClasses['UserPagesHeaderController'] = $IP.'/skins/oasis/modules/UserPagesHeaderController.class.php';
$wgAutoloadClasses['MenuButtonController'] = $IP.'/skins/oasis/modules/MenuButtonController.class.php';
$wgAutoloadClasses['CommentsLikesController'] = $IP.'/skins/oasis/modules/CommentsLikesController.class.php';
$wgAutoloadClasses['BlogListingController'] = $IP.'/skins/oasis/modules/BlogListingController.class.php';
$wgAutoloadClasses['NotificationsController'] = $IP.'/skins/oasis/modules/NotificationsController.class.php';
$wgAutoloadClasses['LatestEarnedBadgesController'] = $IP.'/extensions/wikia/AchievementsII/modules/LatestEarnedBadgesController.class.php';
$wgAutoloadClasses['HotSpotsController'] = $IP.'/skins/oasis/modules/HotSpotsController.class.php';
$wgAutoloadClasses['CommunityCornerController'] = $IP.'/skins/oasis/modules/CommunityCornerController.class.php';
$wgAutoloadClasses['PopularBlogPostsController'] = $IP.'/skins/oasis/modules/PopularBlogPostsController.class.php';
$wgAutoloadClasses['ArticleInterlangController'] = $IP.'/skins/oasis/modules/ArticleInterlangController.class.php';
$wgAutoloadClasses['UploadPhotosController'] = $IP.'/skins/oasis/modules/UploadPhotosController.class.php';
$wgAutoloadClasses['WikiaTempFilesUpload'] = $IP.'/includes/wikia/WikiaTempFilesUpload.class.php';
$wgAutoloadClasses['ThemeSettings'] = $IP.'/extensions/wikia/ThemeDesigner/ThemeSettings.class.php';
$wgAutoloadClasses['ThemeDesignerHelper'] = $IP."/extensions/wikia/ThemeDesigner/ThemeDesignerHelper.class.php";//FB#22659 - dependency for ThemeSettings
$wgAutoloadClasses['ErrorController'] = $IP.'/skins/oasis/modules/ErrorController.class.php';
$wgAutoloadClasses['WikiaMediaCarouselController'] = $IP.'/skins/oasis/modules/WikiaMediaCarouselController.class.php';
$wgAutoloadClasses['LeftMenuController'] = $IP.'/skins/oasis/modules/LeftMenuController.class.php';

// Sass-related classes
$wgAutoloadClasses['SassService']              = $IP.'/includes/wikia/services/sass/SassService.class.php';

// Wikia Style Guide
$wgAutoloadClasses['Wikia\UI\Factory'] = $IP . '/includes/wikia/ui/Factory.class.php';
$wgAutoloadClasses['Wikia\UI\Component'] = $IP . '/includes/wikia/ui/Component.class.php';
$wgAutoloadClasses['Wikia\UI\TemplateException'] = $IP . '/includes/wikia/ui/exceptions/TemplateException.class.php';
$wgAutoloadClasses['Wikia\UI\DataException'] = $IP . '/includes/wikia/ui/exceptions/DataException.class.php';
$wgAutoloadClasses['Wikia\UI\UIFactoryApiController'] = $IP . '/includes/wikia/ui/UIFactoryApiController.class.php';

// Traits
$wgAutoloadClasses['PreventBlockedUsersTrait'] = $IP . '/includes/wikia/traits/PreventBlockedUsersTrait.php';
$wgAutoloadClasses['PreventBlockedUsersThrowsErrorTrait'] = $IP . '/includes/wikia/traits/PreventBlockedUsersTrait.php';
$wgAutoloadClasses['UserAllowedRequirementTrait'] = $IP . '/includes/wikia/traits/UserAllowedRequirementTrait.php';
$wgAutoloadClasses['UserAllowedRequirementThrowsErrorTrait'] = $IP . '/includes/wikia/traits/UserAllowedRequirementTrait.php';
$wgAutoloadClasses['IncludeMessagesTrait'] = $IP . '/includes/wikia/traits/IncludeMessagesTrait.php';
$wgAutoloadClasses['JsonDeserializerTrait'] = "$IP/includes/wikia/traits/JsonDeserializerTrait.php";
$wgAutoloadClasses['TitleTrait'] = $IP . '/includes/wikia/traits/TitleTrait.php';

// Profiler classes
$wgAutoloadClasses['ProfilerData'] = "{$IP}/includes/profiler/ProfilerData.php";
$wgAutoloadClasses['ProfilerDataSink'] = "{$IP}/includes/profiler/sinks/ProfilerDataSink.php";

//RabbitMq
$wgAutoloadClasses['Wikia\Rabbit\ConnectionBase'] = "{$IP}/includes/wikia/rabbitmq/ConnectionBase.class.php";

// Skin loading scripts
$wgHooks['WikiaSkinTopScripts'][] = 'WikiFactoryHubHooks::onWikiaSkinTopScripts';
//$wgHooks['WikiaSkinTopScripts'][] = 'Wikia\\Logger\\Hooks::onWikiaSkinTopScripts';

// Set the WikiaLogger mode early in the setup process
$wgHooks['Debug'][] = 'Wikia\\Logger\\Hooks::onDebug';
$wgHooks['WikiFactory::execute'][] = 'Wikia\\Logger\\Hooks::onWikiFactoryExecute';
$wgHooks['WikiFactory::onExecuteComplete'][] = 'Wikia\\Logger\\Hooks::onWikiFactoryExecuteComplete';

// WikiaTracer
$wgHooks['WebRequestInitialized'][] = 'Wikia\\Tracer\\WikiaTracer::updateInstanceFromMediawiki';
$wgHooks['AfterSetupUser'][] = 'Wikia\\Tracer\\WikiaTracer::updateInstanceFromMediawiki';
$wgHooks['AfterUserLogin'][] = 'Wikia\\Tracer\\WikiaTracer::updateInstanceFromMediawiki';
$wgHooks['BeforeWfShellExec'][] = 'Wikia\\Tracer\\WikiaTracer::onBeforeWfShellExec';
$wgHooks['AfterHttpRequest'][] = 'Wikia\\Tracer\\WikiaTracer::onAfterHttpRequest';

// memcache stats (PLATFORM-292)
$wgAutoloadClasses['Wikia\\Memcached\\MemcachedStats'] = "$IP/includes/wikia/memcached/MemcachedStats.class.php";
$wgHooks['RestInPeace'][] = 'Wikia\\Memcached\\MemcachedStats::onRestInPeace';

# list of groups for wfDebugLog calls that will be logged using WikiaLogger
# @see PLATFORM-424
$wgDebugLogGroups = [
	'ExternalStorage' => true,
	'ExternalStoreDB' => true,
	'MessageCache' => true,
	'poolcounter' => true,  // errors from PoolCounterWork
	'replication' => true,  // replication errros / excessive lags
	'squid' => true,        // timeouts and errors from SquidPurgeClient
	'createwiki' => true,   // CreateWiki process
];

// Register \Wikia\Sass namespace
spl_autoload_register( function( $class ) {
	if ( strpos( $class, 'Wikia\\Sass\\' ) !== false ) {
		$class = preg_replace( '/^\\\\?Wikia\\\\Sass\\\\/', '', $class );
		$file = $GLOBALS['IP'] . '/includes/wikia/services/sass/'.strtr( $class, '\\', '/' ).'.class.php';
		require_once( $file );
		return true;
	}
	return false;
});

// TODO: move this inclusions to includes/wikia/Extensions.php ?
require_once( $IP.'/extensions/wikia/ImageTweaks/ImageTweaks.setup.php' );
require_once( $IP.'/extensions/wikia/Oasis/Oasis_setup.php' );

/**
 * i18n support for jquery.timeago.js (used in History Dropdown)
 */
include_once( "$IP/extensions/wikia/TimeAgoMessaging/TimeAgoMessaging_setup.php" );

/**
 * MW messages in JS
 */
include_once("$IP/extensions/wikia/JSMessages/JSMessages_setup.php");

/**
 * Custom MediaWiki API modules
 */

$wgAutoloadClasses[ "WikiaApiQuery"                 ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQuery.php";
$wgAutoloadClasses[ "WikiaApiQueryDomains"          ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQueryDomains.php";
$wgAutoloadClasses[ "WikiaApiQueryPopularPages"     ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQueryPopularPages.php";
$wgAutoloadClasses[ "WikiaApiQuerySiteInfo"         ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQuerySiteinfo.php";
$wgAutoloadClasses[ "WikiaApiQueryPageinfo"         ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQueryPageinfo.php";
$wgAutoloadClasses[ "WikiaApiCreatorReminderEmail"  ] = "$IP/extensions/wikia/CreateNewWiki/WikiaApiCreatorReminderEmail.php";
$wgAutoloadClasses[ "WikiFactoryTags"               ] = "$IP/extensions/wikia/WikiFactory/Tags/WikiFactoryTags.php";
$wgAutoloadClasses[ "WikiaApiQueryAllUsers"         ] = "$IP/extensions/wikia/WikiaApi/WikiaApiQueryAllUsers.php";
$wgAutoloadClasses[ "ApiFetchBlob"                  ] = "$IP/includes/api/wikia/ApiFetchBlob.php";
$wgAutoloadClasses[ "ApiLicenses"                   ] = "$IP/includes/wikia/api/ApiLicenses.php";
$wgAutoloadClasses['ApiQueryUserGroupMembers'] = "$IP/includes/api/wikia/ApiQueryUserGroupMembers.php";

/**
 * validators
 */
$wgAutoloadClasses[ "WikiaValidator"                ] = "$IP/includes/wikia/validators/WikiaValidator.class.php";
$wgAutoloadClasses[ "WikiaValidationError"          ] = "$IP/includes/wikia/validators/WikiaValidationError.class.php";
$wgAutoloadClasses[ "WikiaValidatorString"          ] = "$IP/includes/wikia/validators/WikiaValidatorString.class.php";
$wgAutoloadClasses[ "WikiaValidatorNumeric"         ] = "$IP/includes/wikia/validators/WikiaValidatorNumeric.class.php";
$wgAutoloadClasses[ "WikiaValidatorInteger"         ] = "$IP/includes/wikia/validators/WikiaValidatorInteger.class.php";
$wgAutoloadClasses[ "WikiaValidatorRegex"           ] = "$IP/includes/wikia/validators/WikiaValidatorRegex.class.php";
$wgAutoloadClasses[ "WikiaValidatorSelect"          ] = "$IP/includes/wikia/validators/WikiaValidatorSelect.class.php";
$wgAutoloadClasses[ "WikiaValidatorMail"            ] = "$IP/includes/wikia/validators/WikiaValidatorMail.class.php";
$wgAutoloadClasses[ "WikiaValidatorUrl"             ] = "$IP/includes/wikia/validators/WikiaValidatorUrl.class.php";
$wgAutoloadClasses[ "WikiaValidatorsSet"            ] = "$IP/includes/wikia/validators/WikiaValidatorsSet.class.php";
$wgAutoloadClasses[ "WikiaValidatorsAnd"            ] = "$IP/includes/wikia/validators/WikiaValidatorsAnd.class.php";
$wgAutoloadClasses[ "WikiaValidatorListBase"        ] = "$IP/includes/wikia/validators/WikiaValidatorListBase.class.php";
$wgAutoloadClasses[ "WikiaValidatorListValue"       ] = "$IP/includes/wikia/validators/WikiaValidatorListValue.class.php";
$wgAutoloadClasses[ "WikiaValidatorCompare"         ] = "$IP/includes/wikia/validators/WikiaValidatorCompare.class.php";
$wgAutoloadClasses[ "WikiaValidatorCompareValueIF"  ] = "$IP/includes/wikia/validators/WikiaValidatorCompareValueIF.class.php";
$wgAutoloadClasses[ "WikiaValidatorCompareEmptyIF"  ] = "$IP/includes/wikia/validators/WikiaValidatorCompareEmptyIF.class.php";
$wgAutoloadClasses[ "WikiaValidatorFileTitle"       ] = "$IP/includes/wikia/validators/WikiaValidatorFileTitle.class.php";
$wgAutoloadClasses[ "WikiaValidatorImageSize"       ] = "$IP/includes/wikia/validators/WikiaValidatorImageSize.class.php";
$wgAutoloadClasses[ "WikiaValidatorDependent"       ] = "$IP/includes/wikia/validators/WikiaValidatorDependent.class.php";
$wgAutoloadClasses[ 'WikiaValidatorRestrictiveUrl'  ] = "$IP/includes/wikia/validators/WikiaValidatorRestrictiveUrl.class.php";
$wgAutoloadClasses[ 'WikiaValidatorUsersUrl'        ] = "$IP/includes/wikia/validators/WikiaValidatorUsersUrl.class.php";
$wgAutoloadClasses[ 'WikiaValidatorAlwaysTrue'      ] = "$IP/includes/wikia/validators/WikiaValidatorAlwaysTrue.class.php";
include_once("$IP/includes/wikia/validators/WikiaValidatorsExceptions.php");

/**
 * MediaWiki Config
 */
$wgAutoloadClasses['Config'] = $IP . '/includes/config/Config.php';
$wgAutoloadClasses['ConfigException'] = $IP . '/includes/config/ConfigException.php';
$wgAutoloadClasses['ConfigFactory'] = $IP . '/includes/config/ConfigFactory.php';
$wgAutoloadClasses['GlobalVarConfig'] = $IP . '/includes/config/GlobalVarConfig.php';

/**
 * registered API methods
 */
global $wgAPIListModules;
$wgAPIListModules[ "wkdomains"    ] = "WikiaApiQueryDomains";
$wgAPIListModules[ "wkpoppages"   ] = "WikiaApiQueryPopularPages";
$wgAPIListModules['groupmembers'] = 'ApiQueryUserGroupMembers';

/**
 * registered API methods
 */
$wgAPIMetaModules[ "siteinfo"     ] = "WikiaApiQuerySiteInfo";
$wgAPIListModules[ "allusers"     ] = "WikiaApiQueryAllUsers";

/**
 * registered Ajax methods
 */
global $wgAjaxExportList;

/**
 * registered Ajax methods
 */
global $wgAPIPropModules;
$wgAPIPropModules[ "info"         ] = "WikiaApiQueryPageinfo";

/**
 * reqistered API modules
 */
global $wgAPIModules;
$wgAPIModules[ "delete"            ] = "ApiDelete";
$wgAPIModules[ "awcreminder"       ] = "WikiaApiCreatorReminderEmail";
$wgAPIModules[ "fetchblob"         ] = "ApiFetchBlob";
$wgAPIModules[ "licenses"          ] = "ApiLicenses";

$wgUseAjax                = true;
$wgValidateUserName       = true;
$wgAjaxAutoCompleteSearch = true;

/**
 * Wikia custom extensions, enabled sitewide. Pre-required by some skins
 */

include_once( "$IP/extensions/ExtensionFunctions.php" );
include_once( "$IP/extensions/wikia/DesignSystem/DesignSystem.setup.php" );
include_once( "$IP/extensions/wikia/AnalyticsEngine/AnalyticsEngine.setup.php" );
include_once( "$IP/extensions/wikia/AjaxFunctions.php" );
include_once( "$IP/extensions/wikia/DataProvider/DataProvider.php" );
include_once( "$IP/extensions/wikia/StaffSig/StaffSig.php" );
include_once( "$IP/extensions/wikia/TagCloud/TagCloudClass.php" );
include_once( "$IP/extensions/wikia/MostPopularCategories/SpecialMostPopularCategories.php" );
include_once( "$IP/extensions/wikia/AssetsManager/AssetsManager_setup.php" );
include_once( "$IP/extensions/wikia/JSSnippets/JSSnippets_setup.php" );
include_once( "$IP/extensions/wikia/SpecialUnlockdb/SpecialUnlockdb.setup.php" );
include_once( "$IP/extensions/wikia/WikiaWantedQueryPage/WikiaWantedQueryPage.setup.php" );
include_once( "$IP/extensions/wikia/ImageServing/imageServing.setup.php" );
include_once( "$IP/extensions/wikia/ImageServing/Test/ImageServingTest.setup.php" );
include_once( "$IP/extensions/wikia/VideoHandlers/VideoHandlers.setup.php" );
include_once( "$IP/extensions/wikia/SpecialUnusedVideos/SpecialUnusedVideos.setup.php" );
include_once( "$IP/extensions/wikia/ArticleSummary/ArticleSummary.setup.php" );
include_once( "$IP/extensions/wikia/FilePage/FilePage.setup.php" );
include_once( "$IP/extensions/wikia/Thumbnails/Thumbnails.setup.php" );
include_once( "$IP/extensions/wikia/InstantGlobals/InstantGlobals.setup.php" );
include_once( "$IP/extensions/wikia/UserTools/UserTools.setup.php" );
include_once( "$IP/extensions/wikia/BannerNotifications/BannerNotifications.setup.php" );
include_once( "$IP/extensions/wikia/AuthModal/AuthModal.setup.php" );
include_once( "$IP/extensions/wikia/LatestPhotos/LatestPhotos.setup.php" );
include_once( "$IP/extensions/wikia/AutoFollow/AutoFollow.setup.php" );
include_once( "$IP/extensions/wikia/WikiaLogo/WikiaLogo.setup.php" );
include_once( "$IP/extensions/wikia/Rail/Rail.setup.php" );
include_once( "$IP/extensions/wikia/PageShare/PageShare.setup.php" );
include_once( "$IP/extensions/wikia/CreateNewWiki/CreateNewWiki_global_setup.php" );
include_once( "$IP/extensions/wikia/Security/Security.setup.php" );
include_once( "$IP/extensions/wikia/CommunityHeader/CommunityHeader.setup.php" );
include_once( "$IP/extensions/wikia/PageHeader/PageHeader.setup.php" );
include_once( "$IP/extensions/wikia/Bucky/Bucky.setup.php" );
include_once( "$IP/extensions/wikia/QuickTools/QuickTools.setup.php" );
include_once( "$IP/extensions/wikia/TOC/TOC.setup.php" );
include_once( "$IP/extensions/wikia/SEOTweaks/SEOTweaks.setup.php" );
include_once( "$IP/extensions/wikia/StaticUserPages/StaticUserPages.setup.php" );

/**
 * @name $wgSkipSkins
 *
 * NOTE: a few wikis may have local override for this var,
 * you need to modify those by hand.
 * A SELECT on city_variables will get you a list.
 */
$wgSkipSkins = array(
		'armchairgm',
		'cars',
		'corporate',
		'corporatebase',
		'corporatehome',
		'curse',
		'entertainment',
		'food',
		'games',
		'gwmonobook',
		'halo',
		'halogamespot',
		'health',
		'home',
		'law',
		'local',
		'memalpha',
		'music',
		'politics',
		'psn',
		'restaurants',
		'searchwikia',
		'search',
		'test',
		'uncyclopedia',
		'lostbook',
		'quartz',
		'monaco_old',
		'smartphone',
		'efmonaco',
		'answers',
		'campfire',
		'wikiamobile',
);

/**
 * @name $wgBiggestCategoriesBlacklist
 * Lists phrases that disqualify a category from appearing in
 * the biggest category list (Monaco sidebar)
 */
$wgBiggestCategoriesBlacklist = array();

/**
 * extensions path as seen by users
 */
$wgExtensionsPath = false; /// defaults to "{$wgScriptPath}/extensions"

/**
 * Auxiliary variables for CreateWikiTask
 */
$wgLangCreationVariables = array();

/**
 * Define Video namespace (used by WikiaVideo extensions)
 * Can not be define directly in extension since it is used in Parser.php and extension is not always enabled
 */
 define('NS_LEGACY_VIDEO', '400');

/**
 * Tasks
 */
require_once( "{$IP}/extensions/wikia/Tasks/Tasks.setup.php");

/**
 * @name wgDBAvgStatusPoll
 * Scale load balancer polling time so that under overload conditions, the database server
 * receives a SHOW STATUS query at an average interval of this many microseconds
 */
$wgDBAvgStatusPoll = 30000;

/**
 * @name wgExternalSharedDB
 * All wikis use shared database to fetch user data
 */
$wgExternalSharedDB = 'wikicities';

/**
 * @name wgDumpsDisabledWikis
 * list of wiki ids not to do dumps for
 */
$wgDumpsDisabledWikis = array();

/**
 * @name wgWikiFactoryTags
 *
 * tags defined in current wiki
 */
$wgWikiFactoryTags = array();

/**
 * external databases
 */
$wgContentReviewDB = 'content_review';
$wgExternalDatawareDB = 'dataware';
$wgExternalArchiveDB = 'archive';
$wgStatsDB = 'stats';
$wgDWStatsDB = 'statsdb';
$wgStatsDBEnabled = true;
$wgSpecialsDB = 'specials';
$wgSharedKeyPrefix = "wikicities"; // default value for shared key prefix, @see wfSharedMemcKey
$wgPortabilityDB = 'portability_db';
$wgForceMasterDatabase = false;  // true only during wiki creation process

/**
 * $wgSharedTables may be customized with a list of tables to share in the shared
 * datbase. However it is advised to limit what tables you do share as many of
 * MediaWiki's tables may have side effects if you try to share them.
 *
 * Wikia change: wikicities.user table is accessed be connecting to $wgExternalSharedDB explicitly.
 */
$wgSharedTables = [];

$wgAutoloadClasses['LBFactory_Wikia'] = "$IP/includes/wikia/LBFactory_Wikia.php";

/**
 * @name wgEnableBlogCommentEdit, wgEnabledGroupedBlogComments, wgEnableBlogWatchlist
 * enable:
 * 	* blog comments edit
 * 	* grouped blog comments in RC
 * 	* added blogs to watchlist
 */
$wgEnableBlogCommentEdit = true;
$wgEnabledGroupedBlogComments = true;
$wgEnableBlogWatchlist = true;
$wgEnableGroupedBlogCommentsWatchlist = false;
$wgEnableGroupedArticleCommentsRC = true;

/**
 * @name wgUseWikiaSearchUI
 * enables wikia Special:Search interface
 */
$wgUseWikiaSearchUI = false;

/**
 * @name: $wgSpecialPagesRequiredLogin
 * list of restricted special pages (dbkey) displayed on Special:SpecialPages which required login
 * @see Login friction project
 */
$wgSpecialPagesRequiredLogin = array('Resetpass', 'MyHome', 'Preferences', 'Watchlist', 'Upload', 'CreateBlogPage', 'CreateBlogListingPage', 'MultipleUpload');

/**
 * @name: $wgArticleCommentsMaxPerPage
 * max comments per page under article
 * @see Article comments
 */
$wgArticleCommentsMaxPerPage = 5;

$wgMaxThumbnailArea = 0.9e7;

/**
 * @name $wgWikiaMaxNameChars
 * soft enforced limit of length for new username
 * @see rt#39263
 */
$wgWikiaMaxNameChars = 50;

/**
 * If this is set to true, then no externals (ads, spotlights, beacons such as google analytics and quantcast)
 * will be used.  This is used to help us get a good baseline for testing performance of in-house stuff only.
 *
 * To change this value, add noexternals=1 to the URL.
 */
$wgNoExternals = false;


/**
 * Style path for resources on the CDN.
 *
 * NOTE: while the normal wgStylePath would include /skins/ in it,
 * this path will NOT have that in it so that CSS and other static
 * files can use a correct local path (such as "/skins/common/blank.gif")
 * which would be a completely functioning local path (which will be prepended
 * in the CSS combiner with wgResourceBasePath).  The advantages of this are two-fold:
 * 1) if the combiner fails to prepend the wgResourceBasePath, the link will still work,
 * 2) the combiner WON'T prepend the wgResourceBasePath on development machines so that
 * the local resource is used (makes testing easier).
 */
$wgResourceBasePath = '';

/**
 * Transpaent 1x1 GIF URI-encoded (BugId:9975)
 */
$wgBlankImgUrl = 'data:image/gif;base64,R0lGODlhAQABAIABAAAAAP///yH5BAEAAAEALAAAAAABAAEAQAICTAEAOw%3D%3D';

/**
 * Serve jQuery from Google's CDN. Disable this variable to serve jQuery as a part of AssetsManager package.
 */
$wgUseJQueryFromCDN = true;

/**
 * The actual path to wikia_combined (without rewrites).  Used for development servers.
 *
 * NOTE: Keep this in sync with the value in /wikia-ops/config/varnish/wikia.vcl
 */
$wgWikiaCombinedPrefix = "index.php?action=ajax&rs=WikiaAssets::combined&";

/**
 * Override MW default enable of EE
 */
$wgUseExternalEditor = false;


/**
 * libmemcached related stuff
 */
define( "CACHE_LIBMEMCACHED", 11 );
$wgObjectCaches[ CACHE_LIBMEMCACHED ] = array( 'factory' => 'LibmemcachedBagOStuff::newFromGlobals' );
$wgSessionsInLibmemcached = false;


$wgSolidCacheType = CACHE_MEMCACHED;
$wgWikiFactoryCacheType = CACHE_MEMCACHED;

/**
 * @name $wgWikiaHideImportsFromIrc
 * hides Special:Import imports from IRC feed.
 * @see rt#43025
 */
$wgWikiaHideImportsFromIrc = true;

/**
 * disable autofollow blogs by default
 */
$wgBlogsEnableStaffAutoFollow = false;

/**
 * @name wgEnableCOPPA
 * toggles COPPA birthyear check on user creation
 */
$wgEnableCOPPA = true;

/**
 * Include helper-functions for allowing SASS to be used
 * in our system.
 */
require_once( "$IP/extensions/wikia/SASS/SassUtil.php" );

/**
 * Default value for ThemeDesigner history
 */
$wgOasisThemeSettingsHistory = array();

/**
 * @name wgPreWikiFactoryValues
 * optionally stores variable values as they were before overridden by WikiFactory
 */
$wgPreWikiFactoryValues = array();

/**
 * @name wgEnableWatchlistNotificationTimeout
 * Toggles watchlist notification timeout hack
 */
$wgEnableWatchlistNotificationTimeout = false;

/**
 * @name wgWatchlistNotificationTimeout
 * Watchlist notification block timeout (in seconds)
 * Default is: 24 hours (but disabled above)
 * @see rt#55604
 */
$wgWatchlistNotificationTimeout = 24 * 60 * 60;

/**
 * @name $wgExcludedWantedFiles
 * don't show those files on Special:WantedFiles
 */
$wgExcludedWantedFiles = array (0 => 'Placeholder', 1 => 'Welcome-user-page');

/**
 *  @name $wgExtensionNamespacesFiles
 * list of namespace localization files for extensions
 */
$wgExtensionNamespacesFiles = array();

/**
 * @name $wgSuppressNamespacePrefix
 * list of namespace that won't display a prefix in the article title shown in Oasis page header
 */
$wgSuppressNamespacePrefix = array();

/**
 * @name $wgMaxCommentsToDelete
 * number of comment to be removed in one request
 */
$wgMaxCommentsToDelete = 100;

/**
 * @name $wgMaxCommentsToMove
 * number of comment to be moved in one request
 */
$wgMaxCommentsToMove = 50;

/**
 * is Semantic Mediawiki uses external database cluster
 * @name $smwgUseExternalDB
 *
 * @see includes/wikia/LBFactory_Multi.php
 */
$smwgUseExternalDB = false;

/**
 * Default value for AB testing array
 */

$wgABTests = array();

/**
 * Memcached client timeouts
 */
$wgMemCachedTimeout = 500000; // stream timeout in microseconds
$wgMemCachedConnectionTimeout = 0.5; // connection timeout in seconds


$wgAssetsManagerQuery = '/__am/%4$d/%1$s/%3$s/%2$s';
//$wgAssetsManagerQuery = '/index.php?action=ajax&rs=AssetsManagerEntryPoint&__am&type=%1$s&cb=%4$d&params=%3$s&oid=%2$s';

/**
 * debug level for memcached
 */
$wgMemCachedDebugLevel = 1;


/**
 * We keep this enabled to support monobook
 **/
$wgEnableMWSuggest = true;

/**
 * enable extension to output OpenGraph meta tags so that facebook sharing
 * and liking works well
 *
 * @name wgEnableOpenGraphMetaExt
 * @see /extensions/OpenGraphMeta
 * @see /extensions/wikia/OpenGraphMetaCustomizations
 */
$wgEnableOpenGraphMetaExt = true;

/**
 * List of internal usernames that shouldn't be allowed in Special:EditCount, e.g. "Default", bots
 * Please use lowercase.
 *
 * @see /extensions/wikia/EditCount/SpecialEditCount_body.php
 */
$wgSpecialEditCountExludedUsernames = array(
	'default'
);

/**
 * List of mobile skins
 */
$wgMobileSkins = array( 'wikiamobile' );

/**
 * variable for enabling Nirvana's API entrypoint wikia.php,
 * requests will be served with a 503 status code if this is false or not set
 * @see wikia.php
 */
$wgEnableNirvanaAPI = true;

/**
 * Array of disabled article actions which will fallback to "view" action (BugId:9964)
 */
$wgDisabledActionsWithViewFallback = array();

/**
 * Disable the slow updating of MySQL search index. We use Lucene/Solr.
 */
$wgDisableSearchUpdate = true;

/**
 * New search code needs a default type to avoid falling back to SearchMySQL.
 */
$wgSearchType = 'SearchEngineDummy';

/**
 * Default settings used by wiki navigation
 */
$wgMaxLevelOneNavElements = 4;
$wgMaxLevelTwoNavElements = 7;
$wgMaxLevelThreeNavElements = 10;

/**
 * Memcached class name
 */

$wgMemCachedClass = 'MemCachedClientforWiki';

/**
 * Extra configuration options for memcached when using libmemcached/pecl-memcached
 */
$wgLibMemCachedOptions = array();

/**
 * Media
 */
$wgAutoloadClasses['WikiaFileHelper'] = $IP.'/includes/wikia/services/WikiaFileHelper.class.php';
$wgAutoloadClasses['ArticlesUsingMediaQuery'] = $IP.'/includes/wikia/ArticlesUsingMediaQuery.class.php';
$wgHooks['ArticleSaveComplete'][] = 'ArticlesUsingMediaQuery::onArticleSaveComplete';
$wgHooks['ArticleDelete'][] = 'ArticlesUsingMediaQuery::onArticleDelete';

/**
 * Password reminder name
 */
$wgPasswordSenderName = Wikia::USER;

/**
 * Defines the mapping for per-skin Common.js/css
 * IMPORTANT: use non-capitalized skin names here!
 *
 * @var array
 */
$wgResourceLoaderAssetsSkinMapping = [
	'oasis' => 'wikia', // in Oasis we use Wikia.js (and Wikia.css) instead of Oasis.js (Oasis.css)
];

/**
 * @see https://wikia.fogbugz.com/default.asp?36946
 * core mediawiki feature variable
 */
$wgArticleCountMethod = "any";

/**
 * Javascript minifier used by ResourceLoader
 * @var false|callback
 */
$wgResourceLoaderJavascriptMinifier = false;

/**
 * CSS minifier used by ResourceLoader
 * @var false|callback
 */
$wgResourceLoaderCssMinifier = false;

/**
 * by default we are not on central wiki
 * @var false|callback
 */
$wgWikiaIsCentralWiki = false;


/**
 * Is bulk mode in Memcached routines enabled?
 * (eg. get_multi())
 * @var boolean
 */
$wgEnableMemcachedBulkMode = false;

/**
 * WikiaSeasons flags
 */
$wgWikiaSeasonsGlobalHeader = false;
$wgWikiaSeasonsWikiaBar = false;
$wgWikiaSeasonsPencilUnit = false;

/**
 * @name $wgEnableWAMPageExt
 * Enables WAMPage extension (corporate pages extension)
 */
$wgEnableWAMPageExt = false;

/**
 * @name $wgWAMPageConfig
 * WAMPage extension configuration -- default configuration
 */
$wgWAMPageConfig = array(
	'pageName' => 'WAM',
	'faqPageName' => 'WAM/FAQ',
	'tabsNames' => array(
		'Top wikis',
		'The biggest gainers',
		'Top video games wikis',
		'Top entertainment wikis',
		'Top lifestyle wikis',
	),
);

/**
 * @name $wgPhalanxService
 * @see extensions/wikia/PhalanxII
 * Use phalanx external service
 */
$wgPhalanxService = true;
$wgPhalanxServiceOptions = [
	'noProxy' => true, # PLATFORM-1744: do not use the default HTTP proxy (defined in $wgHTTPProxy) for Phalanx requests
	'timeout' => 1, # [sec] PLATFORM-2385 / SUS-890: prevent Phalanx slowness from affecting the site performance
	'internalRequest' => true
];

/**
 * @name $wgEnableGoogleFundingChoices
 * Enables Google Funding Choices
 */
$wgEnableGoogleFundingChoices = true;

/**
 * @name $wgEnableGoogleFundingChoicesInHead
 * Enables Google Funding Choices in <head> tag
 */
$wgEnableGoogleFundingChoicesInHead = false;

/**
 * @name $wgEnableNielsen
 * Enables Nielsen Digital Content Ratings
 */
$wgEnableNielsen = false;

/**
 * @name $wgNielsenApid
 * Nielsen Digital Content Ratings apid. Should be changed via WikiFactory when $wgEnableNielsen is set to true
 */
$wgNielsenApid = 'FIXME';


/**
 * @name $wgEnableNetzAthleten
 * Enables NetzAthleten provider
 */
$wgEnableNetzAthleten = true;

/**
 * @name $wgAdDriverIsTestWiki
 * Enables test targeting parameters for wiki.
 */
$wgAdDriverIsAdTestWiki = false;

/**
 * @name $wgAdDriverNetzAthletenCountries
 * Enables NetzAthleten provider in these countries (given $wgEnableNetzAthleten is also true).
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverNetzAthletenCountries = null;

/**
 * @name wgAdDriverA9VideoBidderCountries
 * List of countries where A9 video bidding platform is enabled.
 * It won't work if A9 display bidder isn't enabled
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverA9VideoBidderCountries = [];

/**
 * @name $wgAdDriverA9BidderCountries
 * List of countries where A9 bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverA9BidderCountries = null;

/**
 * @name $wgAdDriverEnableRubiconFastlane
 * Enables Rubicon Fastlane
 */
$wgAdDriverEnableRubiconFastlane = true;

/**
 * @name $wgAdDriverRubiconFastlaneCountries
 * Enables RubiconFastlane in these countries (given wgAdDriverEnableRubiconFastlane is also true).
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverRubiconFastlaneCountries = null;

/**
 * @name $wgAdDriverRubiconDisplayPrebidCountries
 * Enables Rubicon (Prebid) implementation and DISABLES the old one in these countries.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverRubiconDisplayPrebidCountries = null;

/**
 * @name $wgAdDriverRubiconFastlaneProviderCountries
 * Enables RubiconFastlane provider in these countries.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverRubiconFastlaneProviderCountries = null;

/**
 * @name $wgAdDriverRubiconFastlaneProviderSkipTier
 * Sets minimum value of tier needed to render an ad.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverRubiconFastlaneProviderSkipTier = 0;

/**
 * @name $wgAdDriverRubiconPrebidCountries
 * Enables Rubicon (Prebid) in these countries.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverRubiconPrebidCountries = null;

/**
 * @name $wgAdDriverPrebidBidderCountries
 * List of countries where prebid bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverPrebidBidderCountries = null;

/**
 * @name $wgAdDriverAolBidderCountries
 * List of countries where aol bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverAolBidderCountries = null;

/**
 * @name $wgAdDriverAolOneMobileBidderCountries
 * List of countries where onemobile bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverAolOneMobileBidderCountries = null;

/**
 * @name $wgAdDriverAppNexusBidderCountries
 * List of countries where appnexus bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverAppNexusBidderCountries = null;

/**
 * @name $wgAdDriverAppNexusWebAdsBidderCountries
 * List of countries where appnexus via WebAds bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverAppNexusWebAdsBidderCountries = null;

/**
 * @name $wgAdDriverUseAudienceNetworkBidder
 * Enables Facebook Audience Network bidding platform.
 */
$wgAdDriverUseAudienceNetworkBidder = false;

/**
 * @name $wgAdDriverAudienceNetworkBidderCountries
 * List of countries where Facebook Audience Network bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverAudienceNetworkBidderCountries = null;

/**
 * @name $wgAdDriverBeachfrontBidderCountries
 * List of countries where Beachfront bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverBeachfrontBidderCountries = null;

/**
 * @name $wgAdDriverBottomLeaderBoardOnMobileCountries
 * List of countries where BOTTOM_LEADERBOARD ad slot is enabled on mobile-wiki.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverBottomLeaderBoardOnMobileCountries = null;

/**
 * @name $wgAdDriverBottomLeaderBoardMegaCountries
 * List of countries where BOTTOM_LEADERBOARD ad slot mega is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverBottomLeaderBoardMegaCountries = null;

/**
 * @name $wgAdDriverIndexExchangeBidderCountries
 * List of countries where indexExchange bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverIndexExchangeBidderCountries = null;

/**
 * @name $wgAdDriverOpenXPrebidBidderCountries
 * List of countries where OpenX prebid bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverOpenXPrebidBidderCountries = null;

/**
 * @name $wgAdDriverPubMaticBidderCountries
 * List of countries where PubMatic prebid bidding platform is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverPubMaticBidderCountries = null;

/**
 * @name $wgAdDriverOutstreamVideoFrequencyCapping
 * Supported values 1/4pv, 2/5min
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverOutstreamVideoFrequencyCapping = null;

/**
 * @name $wgAdPageLevelCategoryLangs
 * Enables DART category page param for these content languages
 * "Utility" var, don't change it here.
 */
$wgAdPageLevelCategoryLangs = [ 'en' ];

/**
 * @name $wgEnableJavaScriptErrorLogging
 * Enables JavaScript error logging mechanism
 */
$wgEnableJavaScriptErrorLogging = false;

/**
 * @name $wgAdDriverDelayBelowTheFold
 * Prevents from loading BTF before ATF ad slots
 */
$wgAdDriverDelayBelowTheFold = true;

/**
 * @name $wgAdDriverEnableInvisibleHighImpactSlot
 * Enables INVISIBLE_HIGH_IMPACT slot
 */
$wgAdDriverEnableInvisibleHighImpactSlot = true;

/**
 * @name $wgAdDriverUseAdsAfterInfobox
 * Enable new mobile_in_content slot after infobox placement
 */
$wgAdDriverUseAdsAfterInfobox = false;

/**
 * @name $wgAdDriverUseEvolve2
 * Whether to enable AdProviderEvolve2 (true) or not (false)
 */
$wgAdDriverUseEvolve2 = true;

/**
 * @name $wgAdDriverEvolve2Countries
 * List of countries with enabled Evolve2 module.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverEvolve2Countries = null;

/** @name $wgSitewideDisableAdsOnMercury
 * Disable ads on Mercury if set to true.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgSitewideDisableAdsOnMercury = false;

/**
 * @name $wgSitewideDisableGpt
 * @link https://wikia-inc.atlassian.net/wiki/display/ADEN/Disaster+Recovery
 * @link http://community.wikia.com/wiki/Special:WikiFactory/community/variables/wgSitewideDisableGpt
 *
 * Disable all GPT (DART) ads sitewide in case a disaster happens.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 * For more details consult https://wikia-inc.atlassian.net/wiki/display/ADEN/Disaster+Recovery
 */
$wgSitewideDisableGpt = false;

/**
 * @name $wgEnableKruxTargeting
 *
 * Enables Krux Targeting
 */
$wgEnableKruxTargeting = true;

/**
 * @name $wgSitewideDisableKrux
 * @link https://wikia-inc.atlassian.net/wiki/display/ADEN/Disaster+Recovery
 * @link http://community.wikia.com/wiki/Special:WikiFactory/community/variables/wgSitewideDisableKrux
 *
 * Disable Krux sitewide in case a disaster happens.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 * For more details consult https://wikia-inc.atlassian.net/wiki/display/ADEN/Disaster+Recovery
 */
$wgSitewideDisableKrux = false;

/**
 * @name $wgAdDriverKikimoraTrackingCountries
 * Enables warehouse tracking of ad related info
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 * @link https://wikia-inc.atlassian.net/wiki/display/ADEN/Disaster+Recovery
 * @link http://community.wikia.com/wiki/Special:WikiFactory/community/variables/wgAdDriverKikimoraTrackingCountries
 */
$wgAdDriverKikimoraTrackingCountries = [];


/**
 * @name $wgAdDriverScrollDepthTrackingCountries
 * List of countries where scroll depth tracking is enabled.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverScrollDepthTrackingCountries = null;

/**
 * @name $wgAdDriverSrcPremiumCountries
 * Enables setting src=premium param for all ad slots on page, when premium video
 * is present on that page.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverSrcPremiumCountries = [];

/**
 * @name $wgAdDriverKILOCountries
 * Enables transitional to MEGA way of building adUnits (for GPT provider, NOT ONLY REMNANT).
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverKILOCountries = null;

/**
 * @name $wgAdDriverMEGACountries
 * Enables new way of building adUnits (currently limited for remnant).
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMEGACountries = null;

/**
 * @name $wgAdDriverMegaAdUnitBuilderForFVCountries
 * Enables new way of building adUnits for FeaturedVideo pages.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMegaAdUnitBuilderForFVCountries = null;

/**
 * @name $wgAdDriverMoatTrackingForFeaturedVideoAdCountries
 * Enables MOAT tracking for feature video ads.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMoatTrackingForFeaturedVideoAdCountries = null;

/**
 * @name $wgAdDriverMoatTrackingForFeaturedVideoAdSampling
 * Percentage value of users with MOAT tracking for featured video.
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMoatTrackingForFeaturedVideoAdSampling = null;

/**
 * @name $wgAdDriverTrackState
 * Enables GA tracking of state for ad slots on pages
 */
$wgAdDriverTrackState = false;

/**
 * @name $wgAdDriverForcedProvider
 * Forces to use passed provider for all slots managed by this provider and disables other providers.
 */
$wgAdDriverForcedProvider = null;

/**
 * @name $wgAdDriverEnableAdsInMaps
 * Whether to display ads within interactive maps
 */
$wgAdDriverEnableAdsInMaps = true;

/**
 * @name $wgAdDriverDelayCountries
 * List of countries with enabled AdEngine delay
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverDelayCountries = null;

/**
 * @name $wgAdDriverDelayTimeout
 * AdEngine delay timeout (in ms)
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverDelayTimeout = 2000;

/**
 * @name $wgAdDriverKruxCountries
 * List of countries Krux will be enabled on
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverKruxCountries = null;

/**
 * @name $wgHighValueCountries
 * List of countries defined as high-value for revenue purposes
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgHighValueCountries = null;

/**
 * @name $wgAdDriverTurtleCountries
 * List of countries to call Turtle ad partner in
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverTurtleCountries = null;

/**
 * @name wgAdDriverEnableInstartLogicRecovery
 * Enable InstartLogic Recovery per wiki
 */
$wgAdDriverEnableInstartLogicRecovery = true;

/**
 * @name $wgAdDriverPageFairDetectionCountries
 * List of countries to call PageFair detection scripts
 */
$wgAdDriverPageFairDetectionCountries = null;

/**
 * @name $wgAdDriverPageFairRecoveryCountries
 * List of countries to call ads through PageFair
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverPageFairRecoveryCountries = null;

/**
 * @name $wgAdDriverEnablePageFairRecovery
 * Enable PageFair Recovery per wiki
 */
$wgAdDriverEnablePageFairRecovery = true;

/**
 * @name $wgAdDriverBabDetectionDesktopCountries
 * List of countries to call BlockAdBlock detection scripts on oasis
 */
$wgAdDriverBabDetectionDesktopCountries = null;

/**
 * @name $wgAdDriverBabDetectionMobileCountries
 * List of countries to call BlockAdBlock detection scripts on mobile-wiki
 */
$wgAdDriverBabDetectionMobileCountries = null;

/**
 * @name $wgAdDriverF2BabDetectionCountries
 * List of countries to call BlockAdBlock detection scripts on news&stories
 */
$wgAdDriverF2BabDetectionCountries = null;

/**
 * @name $wgAdDriverBabRecoveryCountries
 * List of countries to put recovery behind BlockAdBlock detection
 */
$wgAdDriverBabRecoveryCountries = null;

/**
 * trusted proxy service registry
 */
$wgAutoloadClasses[ 'TrustedProxyService'] =  "$IP/includes/wikia/services/TrustedProxyService.class.php" ;
$wgHooks['IsTrustedProxy'][] = 'TrustedProxyService::onIsTrustedProxy';

/**
 * @name $wgChatDebugEnabled
 * Enables verbose logging from chat
 */
//$wgChatDebugEnabled = true;

/**
 * @name $wgPagesWithNoAdsForLoggedInUsersOverriden
 * Override ad level for a (set of) specific page(s)
 * Use case: sponsor ads on a landing page targeted to Wikia editors (=logged in)
 * eg. array('Grand_Theft_Auto_V')
 */
$wgPagesWithNoAdsForLoggedInUsersOverriden = array();

/**
 * @name $wgPagesWithNoAdsForLoggedInUsersOverriden_AD_LEVEL
 * Ad level to be forced
 * Default is 'corporate' (LB+MR+skin only); 'all' may be useful sometimes
 */
$wgPagesWithNoAdsForLoggedInUsersOverriden_AD_LEVEL = null;

/**
 * @name $wgOasisResponsive
 * Enables the Oasis responsive layout styles
 */
$wgOasisResponsive = true;

/**
 * @name $wgDisableReportTime
 * Turns off <!-- Served by ... in ... ms --> HTML comment
 */
$wgDisableReportTime = true;

/**
 * @name $wgInvalidateCacheOnLocalSettingsChange
 * Setting this to true will invalidate all cached pages whenever LocalSettings.php is changed.
 */
$wgInvalidateCacheOnLocalSettingsChange = false;

/**
 * Set to true to enable user-to-user e-mail.
 * This can potentially be abused, as it's hard to track.
 */
$wgEnableUserEmail = false;

/**
 * Enables ETag globally
 *
 * @see http://www.mediawiki.org/wiki/Manual:$wgUseETag
 *
 * $wgUseETag is a core MW variable initialized in includes/DefaultSettings.php
 */
$wgUseETag = true;

/**
 * Restrictions for some api methods
 */
$wgApiAccess = [
	'SearchApiController' => [
		'getCrossWiki' => ApiAccessService::WIKIA_CORPORATE,
		'getList' => ApiAccessService::WIKIA_NON_CORPORATE,
	],
	'SearchSuggestionsApiController' => ApiAccessService::WIKIA_NON_CORPORATE,
	'MoviesApiController' => ApiAccessService::WIKIA_CORPORATE,
	'WAMApiController' => ApiAccessService::WIKIA_CORPORATE,
	'WikisApiController' => ApiAccessService::WIKIA_CORPORATE,
	'DesignSystemApiController' => ApiAccessService::WIKIA_CORPORATE
];

/**
 * First matched rule will have an effect. All other rules will be ignored.
 */
$wgNirvanaAccessRules = [
	/* You don't need any permissions to login. */
	[
		"class" => "UserLoginController",
		"method" => "*",
		"requiredPermissions" => [],
	],
	[
		"class" => "UserLoginSpecialController",
		"method" => "*",
		"requiredPermissions" => [],
	],
	[
		"class" => "UserSignupSpecialController",
		"method" => "*",
		"requiredPermissions" => [],
	],
	/* We need oasis controller to render  */
	[
		"class" => "OasisController",
		"method" => "*",
		"requiredPermissions" => [],
	],
	/* Catch all statement. By default all controllers and services require read permission. */
	[
		"class" => "*",
		"method" => "*",
		"requiredPermissions" => ["read"],
	],
];

/**
 * @name $wgEnableLyricsApi
 * Enables Lyrics API extension (new Lyrics Wikia API)
 */
$wgEnableLyricsApi = false;

/**
 * @name $wgLyricsItunesAffiliateToken
 * iTunes affiliate token needed in new Lyrics API
 */
$wgLyricsItunesAffiliateToken = '';

/**
 * @name wgEnableSpecialSearchCaching
 * Enables caching of search results on CDN
 */
$wgEnableSpecialSearchCaching = true;

/**
 * @name wgBuckySampling
 * Sets the sampling rate for Bucky reporting, sampling applied at each page view.
 * Unit: percent (100 = all, 1 = 1%, 0.1 = 0.1%)
 */
$wgBuckySampling = 10;

/**
 * @name wgBuckyEnabledSkins
 * List of skins where Bucky reporting should be enabled
 */
$wgBuckyEnabledSkins = [
	'monobook',
	'oasis',
	'uncyclopedia',
	'wikiamobile',
];

/**
 * @name wgMemcacheStatsSampling
 * Sets the sampling rate for Memcache stats reporting, sampling applied at each page view
 *
 * Unit: percent (0-100)
 */
$wgMemcacheStatsSampling = 1;

/**
 * @name wgXhprofUDPHost
 * Host that xhprof data should be reported to (if set to null will use $wgUDPProfilerHost)
 */
$wgXhprofUDPHost = null;

/**
 * @name wgXhprofUDPPort
 * Port that xhprof data should be reported to
 */
$wgXhprofUDPPort = '3911';

/**
 * @name wgXhprofMinimumTime
 * Threshold for total time spent in function to be reported (set to 0 to report all entries)
 */
$wgXhprofMinimumTime = 0.001;

/**
 * Force ImageServing to return an empty list
 * see PLATFORM-392
 */
$wgImageServingForceNoResults = false;

/**
 * @name wgOasisTypography
 * Enable typography changes on oasis breakpoints.
 * Works only if wgOasisBreakpoints set to true
 */
$wgOasisTypography = true;

/**
 * Force new breakpoints $wgOasisBreakpoints
 * see CONCF-186
 * todo Remove when removing responsive
 */
$wgOasisBreakpoints = true;

/**
 * @name $wgAdDriverHighImpact2SlotCountries
 * Enables INVISIBLE_HIGH_IMPACT_2 slot in these countries
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverHighImpact2SlotCountries = null;

/**
 * @name $wgAdDriverMobileTransitionInterstitialCountries
 * Enables Mercury Interstitial inside INVISIBLE_HIGH_IMPACT_2 on transition.
 * Works only when $wgAdDriverHighImpact2SlotCountries is set to true/current geo.
 * Don't combine with $wgAdDriverMobileFloorAdhesionCountries
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMobileTransitionInterstitialCountries = null;

/**
 * @name $wgAdDriverMobileFloorAdhesionCountries
 * Enables Mercury FloorAdhesion inside INVISIBLE_HIGH_IMPACT_2 on transition.
 * Works only when $wgAdDriverHighImpact2SlotCountries is set to true/current geo.
 * Don't combine with $wgAdDriverMobileTransitionInterstitialCountries
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverMobileFloorAdhesionCountries = null;

/**
 * @name $wgAdDriverIncontentPlayerSlotCountries
 * Enables INCONTENT_PLAYER slot in these countries
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgAdDriverIncontentPlayerSlotCountries = null;

/**
 * manage a user's preferences externally
 */
$wgPreferenceServiceRead = false;
$wgPreferenceServiceWrite = true;

/**
 * @name $wgEnableFliteTagExt
 *
 * Enables FliteTag extension which makes it possible to use <flite> tag within an article content
 */
$wgEnableFliteTagExt = false;

/**
 * @name $wgARecoveryEngineCustomLog
 * Enables Kibana logging of ad recovery interruptions
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgARecoveryEngineCustomLog = null;

/**
 * Protect Piggyback logs even if the extension is disabled
 */
$wgLogRestrictions['piggyback'] = 'piggyback';

/**
 * Protect chatconnect logs even if the extension is disabled
 */
$wgLogRestrictions["chatconnect"] = 'checkuser';

/**
 * Protect editaccnt logs even if the extension is disabled
 */
$wgLogRestrictions['editaccnt'] = 'editaccount';

/**
 * Protect phalanx logs even if the extension is disabled
 */
$wgLogRestrictions['phalanx'] = 'phalanx';
$wgLogRestrictions['phalanxemail'] = 'phalanxemailblock';

/**
 * Protect StaffLog even if the extension is disabled
 */
$wgLogRestrictions['StaffLog'] = 'StaffLog';

/**
 * Reject attempts to fall back to the MediaWiki session for authentication.
 */
$wgRejectAuthenticationFallback = true;

/**
 * @name $wgEnableHostnameInHtmlTitle
 *
 * Whether to include the hostname in HTML <title> tag.
 * On production this is overridden and false.
 */
$wgEnableHostnameInHtmlTitle = true;

/**
 * Use template types from Template Classification Service in MW context
 */
include_once("$IP/includes/wikia/parser/templatetypes/TemplateTypes.setup.php");

/**
 * @name $wgEnableReviveSpotlights
 * Enables Revive Spotlights
 */
$wgEnableReviveSpotlights = true;

/**
 * @name $wgReviveSpotlightsCountries
 * Enables Revive Spotlights in these countries (given wgEnableReviveSpotlights is also true).
 * ONLY UPDATE THROUGH WIKI FACTORY ON COMMUNITY - it's an instant global.
 */
$wgReviveSpotlightsCountries = null;

/**
 * @name $wgDisableImprovedGenderSupport
 *
 * Allow to disable "improved" gender support included in MW 1.18
 * Setting this to FALSE will display user/user talk namespaces according to the user's gender as
 * set in preferences, for languages which support it
 *
 * @see https://www.mediawiki.org/wiki/MediaWiki_1.18#Better_gender_support
 * @see https://wikia-inc.atlassian.net/browse/SUS-3131
 * @see Title::getNsText()
 */
$wgDisableImprovedGenderSupport = true;

/**
 * @name $wgAutoapproveJS
 * Enables autoapproving JS pages changes
 */
$wgAutoapproveJS = false;

/**
 * @name $wgWikiaBaseDomainRegex
 * A central regex string for use in domain checking, so we can easily
 * update/add/change domains in the future
 */
$wgWikiaBaseDomainRegex = '(wikia\\.com|wikia-staging\\.com|wikia-dev\\.(com|us|pl))';


$wgEnableOpenXSPC = true;

/**
 * Enable recovery
 * It should be always included even if recovery is disabled as we use Recovery classes outside the module
 */
include_once("$IP/extensions/wikia/ARecoveryEngine/ARecoveryEngine.setup.php");

include_once "$IP/extensions/wikia/ImageReview/ImageReviewEvents.setup.php";

// SUS-2164: Include Facebook extensions - enabled globally
include_once "$IP/extensions/wikia/FacebookPreferences/FacebookPreferences.setup.php";
include_once "$IP/extensions/wikia/FacebookTags/FacebookTags.setup.php";

// SUS-2956: Include MultiLookup extension
include_once "$IP/extensions/wikia/SpecialMultipleLookup/SpecialMultipleLookup.php";

// SUS-3475: Extension to update shared city_list table
include_once "$IP/extensions/wikia/CityList/CityList.setup.php";

// SUS-3496: Extension to update shared dataware.pages table
include_once "$IP/extensions/wikia/Pages/Pages.setup.php";

// SUS-3455: Special:ListGlobalUsers for all wikis
include_once "$IP/extensions/wikia/ListGlobalUsers/ListGlobalUsers.setup.php";

// SRE-76: Logging classes that have been initially defined in config.
$wgAutoloadClasses['AuditLog'] = "$IP/includes/wikia/AuditLog.class.php";

$wgHooks['SetupAfterCache'][] = 'AuditLog::init';
