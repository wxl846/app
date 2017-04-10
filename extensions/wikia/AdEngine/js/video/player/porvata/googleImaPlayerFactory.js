/*global define, google*/
define('ext.wikia.adEngine.video.player.porvata.googleImaPlayerFactory', [
	'ext.wikia.adEngine.video.player.porvata.googleImaSetup',
	'ext.wikia.adEngine.video.player.porvata.moatVideoTracker',
	'wikia.document',
	'wikia.log'
], function (imaSetup, moatVideoTracker, doc, log) {
	'use strict';
	var logGroup = 'ext.wikia.adEngine.video.player.porvata.googleImaPlayerFactory';

	function create(adDisplayContainer, adsLoader, videoSettings) {
		var params = videoSettings.getParams(),
			isAdsManagerLoaded = false,
			status = '',
			videoMock = doc.createElement('video'),
			adsManager,
			mobileVideoAd = params.container.querySelector('video'),
			eventListeners = {};

		function adsManagerLoadedCallback(adsManagerLoadedEvent) {
			adsManager = adsManagerLoadedEvent.getAdsManager(videoMock, imaSetup.getRenderingSettings(params));
			isAdsManagerLoaded = true;

			if (videoSettings.isMoatTrackingEnabled()) {
				moatVideoTracker.init(adsManager, params.container, google.ima.ViewMode.NORMAL, params.src, params.slotName);
			}

			log('AdsManager loaded', log.levels.debug, logGroup);
		}

		function addEventListener(eventName, callback) {
			log(['addEventListener to AdManager', eventName], log.levels.debug, logGroup);

			if (eventName.indexOf('wikia') !== -1) {
				eventListeners[eventName] = eventListeners[eventName] || [];
				eventListeners[eventName].push(callback);
				return;
			}

			if (isAdsManagerLoaded) {
				adsManager.addEventListener(eventName, callback);
			} else {
				adsLoader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, function () {
					adsManager.addEventListener(eventName, callback);
				});
			}
		}

		function removeEventListener(eventName, callback) {
			log(['removeEventListener to AdManager', eventName], log.levels.debug, logGroup);

			if (eventListeners[eventName]) {
				var listenerId = eventListeners[eventName].indexOf(callback);
				if (listenerId !== -1) {
					eventListeners[eventName].splice(listenerId, 1);
				}
				return;
			}

			if (isAdsManagerLoaded) {
				adsManager.removeEventListener(eventName, callback);
			} else {
				adsLoader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, function () {
					adsManager.removeEventListener(eventName, callback);
				});
			}
		}

		function setAutoPlay(value) {
			// mobileVideoAd DOM element is present on mobile only
			if (mobileVideoAd) {
				mobileVideoAd.autoplay = value;
				mobileVideoAd.muted = value;
			}
		}

		function playVideo(width, height) {
			function callback() {
				var roundedWidth = Math.round(width),
					roundedHeight = Math.round(height);

				log(['Video play: prepare player UI', roundedWidth, roundedHeight], log.levels.debug, logGroup);
				dispatchEvent('wikiaAdPlayTriggered');

				// https://developers.google.com/interactive-media-ads/docs/sdks/html5/v3/apis#ima.AdDisplayContainer.initialize
				adDisplayContainer.initialize();
				adsManager.init(roundedWidth, roundedHeight, google.ima.ViewMode.NORMAL);
				adsManager.start();
				adsLoader.removeEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, callback);

				log('Video play: started', log.levels.debug, logGroup);
			}

			if (isAdsManagerLoaded) {
				callback();
			} else {
				// When adsManager is not loaded yet video can't start without click on mobile
				// Muted auto play is workaround to run video on adsManagerLoaded event
				setAutoPlay(true);
				adsLoader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, callback, false);
				log(['Video play: waiting for full load of adsManager'], log.levels.debug, logGroup);
			}
		}

		function reload() {
			adsManager.destroy();
			adsLoader.contentComplete();
			adsLoader.requestAds(imaSetup.createRequest(params));

			log('IMA player reloaded', log.levels.debug, logGroup);
		}

		function resize(width, height) {
			var roundedWidth = Math.round(width),
				roundedHeight = Math.round(height);

			if (adsManager) {
				adsManager.resize(roundedWidth, roundedHeight, google.ima.ViewMode.NORMAL);

				log(['IMA player resized', roundedWidth, roundedHeight], log.levels.debug, logGroup);
			}
		}

		function dispatchEvent(eventName) {
			if (eventListeners[eventName] && eventListeners[eventName].length > 0) {
				eventListeners[eventName].forEach(function (callback) {
					callback({});
				});
			}
		}

		function setStatus(newStatus) {
			return function () {
				status = newStatus;
			};
		}

		function getStatus() {
			return status;
		}

		function getAdsManager() {
			return adsManager;
		}

		adsLoader.addEventListener(
			google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED,
			adsManagerLoadedCallback,
			false
		);

		adsLoader.requestAds(imaSetup.createRequest(params));
		if (videoSettings.isAutoPlay()) {
			setAutoPlay(true);
		}

		addEventListener(google.ima.AdEvent.Type.RESUMED, setStatus('playing'));
		addEventListener(google.ima.AdEvent.Type.STARTED, setStatus('playing'));
		addEventListener(google.ima.AdEvent.Type.PAUSED, setStatus('paused'));
		addEventListener(google.ima.AdEvent.Type.COMPLETE, setStatus('completed'));

		return {
			addEventListener: addEventListener,
			dispatchEvent: dispatchEvent,
			getAdsManager: getAdsManager,
			getStatus: getStatus,
			playVideo: playVideo,
			reload: reload,
			removeEventListener: removeEventListener,
			resize: resize,
			setAutoPlay: setAutoPlay
		};
	}

	return {
		create: create
	};
});
