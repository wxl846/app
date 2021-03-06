/*global define*/
define('ext.wikia.adEngine.ml.nivens.mobileNivensInputParser', [
	'ext.wikia.adEngine.adLogicPageParams',
	'wikia.browserDetect',
	'wikia.geo',
	'wikia.log'
], function (pageParams, browserDetect, geo, log) {
	'use strict';

	var logGroup = 'ext.wikia.adEngine.ml.nivens.mobileNivensInputParser';

	function getData() {
		var data,
			params = pageParams.getPageLevelParams();

		data = [
			browserDetect.getBrowser().indexOf('Chrome') === 0 ? 1 : 0,
			browserDetect.getBrowser().indexOf('Safari') === 0 ? 1 : 0,
			geo.getCountryCode() === 'US' ? 1 : 0,
			geo.getCountryCode() ? 0 : 1,
			params.s0v === 'comics' ? 1 : 0,
			params.s0v === 'games' ? 1 : 0,
			params.s0v === 'lifestyle' ? 1 : 0,
			params.s0v === 'music' ? 1 : 0,
			params.esrb === 'mature' ? 1 : 0,
		];

		log(['Mobile nivens data', data], log.levels.debug, logGroup);

		return data;
	}

	return {
		getData: getData
	};
});
