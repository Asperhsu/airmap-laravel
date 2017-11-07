var debounce = require('debounce');

var MapHandler = (function(){
	var userOptions = {};
	var instance = null;
	var googleApi = null;
	var language = 'zh-TW';
	var container = '#map-container';
	var bounds;

	var getMapOption = function(userOptions){
		var options = {
			streetViewControl: true,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: googleApi.MapTypeControlStyle.HORIZONTAL_BAR,
				position: googleApi.ControlPosition.TOP_RIGHT,
				mapTypeIds: [
					googleApi.MapTypeId.ROADMAP, 
					googleApi.MapTypeId.SATELLITE, 
					googleApi.MapTypeId.HYBRID, 
					googleApi.MapTypeId.TERRAIN,
				]
			},
			zoomControl: true,
			zoomControlOptions: {
				position: googleApi.ControlPosition.RIGHT_BOTTOM
			},
			scaleControl: true,
			center: {lat: 23.839775, lng: 121.062213},
			zoom: 7,
			styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#4f595d"},{"visibility":"on"}]}]
		};

		if(userOptions){ options = $.extend({}, options, userOptions); }

		return options;
	};

	var addUserLocationButton = function(map){				
		var findZoomLevelByAccuracy = function(accuracy){
			if( parseFloat(accuracy) <= 0 ){ return 12; }
			//591657550.500000 / 2^(level-1)
			var level = ( Math.log(591657550.500000/accuracy) / Math.log(2) ) + 1;
			return Math.floor(level);
		};

		var $element = $([
			"<div id='geoLocate'>",
			"<button>", 
			"<div class='icon-gps'></div>",
			"</button>",
			"</div>"
		].join(''));
		var $icon = $element.find(".icon-gps");
		
		googleApi.event.addListener(map, 'dragend', function() {
			$icon.removeClass('gps-located gps-unlocate');
		});

		var animateInterval;
		$element.find("button").click(function(){
			if(animateInterval){				
				$icon.removeClass('gps-located gps-unlocate');
				clearInterval(animateInterval);
				animateInterval = null;
				return;
			}

			animateInterval = setInterval(function(){
				if( $icon.hasClass('gps-unlocate') ){
					$icon.removeClass('gps-unlocate');
				}else{
					$icon.addClass('gps-unlocate');
				}
			}, 500);

			var latlng = $icon.data('latlng');
			var zoom = $icon.data('zoom');
			if(latlng){
				map.setCenter(latlng);
				map.setZoom(zoom || 12);

				$icon.removeClass('gps-unlocate').addClass('gps-located');
				clearInterval(animateInterval);
				animateInterval = null;
				return; 
			}

			// var url = "http://ip-api.com/json";
			var url = "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyCDRRT8it4AZpwbORhHeqoi2qrWDmQqD48";
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: url
			}).success(function(data){
				$icon.removeClass('gps-located gps-unlocate');
				
				if( data.location.lat && data.location.lng ){
					latlng = new googleApi.LatLng(data.location.lat, data.location.lng);
					zoom = findZoomLevelByAccuracy(data.accuracy);
					if( zoom > 14 ){ zoom = 14; }

					var marker = new googleApi.Marker({
						position: latlng,
						map: map,
						icon: {
							path: google.maps.SymbolPath.CIRCLE,
							fillColor: "#4285F4",
							fillOpacity: 1,
							scale: 8,
							strokeColor: "#FFFFFF",
							strokeWeight: 1,
						}
					});

					var circle = new googleApi.Circle({
						center: latlng,
						radius: data.accuracy,
						map: map,
						fillColor: "#4285F4",
						fillOpacity: 0.1,
						strokeColor: "#4285F4",
						strokeOpacity: 0.2
					});

					map.setCenter(latlng);
					map.setZoom(zoom);

					$icon.data('latlng', latlng).data('zoom', zoom).addClass('gps-located');
				}
			}).complete(function(){
				clearInterval(animateInterval);
				animateInterval = null;
			});	
		});

		var controlDiv = $element[0];
		controlDiv.index = 1;
		map.controls[googleApi.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
	};

	var registerBoundsChanged = function () {
		googleApi.event.addListener(instance, 'bounds_changed', function () {
			var status = 'larger';
			var newBounds = instance.getBounds().toJSON();

			if (newBounds.east < bounds.east
				&& newBounds.east < bounds.east
				&& newBounds.east < bounds.east
				&& newBounds.east < bounds.east){
					status = 'smaller';
				}

			$("body").trigger("mapBoundsChanged", [status]);
			bounds = newBounds;
		});
	}

	var initMap = function(){
		googleApi = google.maps;
		var options = $.extend({}, getMapOption(), userOptions);		
		
		instance = new googleApi.Map(
			document.getElementById(container.replace('#', '')), 
			options
		);

		addUserLocationButton(instance);

		googleApi.event.addListenerOnce(instance, 'idle', function () {
			bounds = instance.getBounds().toJSON();
			$("body").trigger("mapBootCompelete");
			registerBoundsChanged();
		});
	};

	var loadScript = function() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '//maps.googleapis.com/maps/api/js?key=AIzaSyBfhb3bOt_jBPFN2WDzkhX8k518Yc7CLBw&callback=MapHandler.initMap';
        script.src += '&language=' + language;
        
        script.id = "google-maps-script";
        document.body.appendChild(script);
    } 

	return {
		boot: function(options){
			userOptions = options || {};
			//loadScript();
		},
		getContainer: function(){
			return container;
		},
		getInstance: function(){
			return instance;
		},
		getApi: function(optons){
			return googleApi;
		},
		createLatLng: function(lat, lng){
			return new googleApi.LatLng(lat, lng);
		},
		createSize: function(w, h){
			return new googleApi.Size(w, h);
		},
		createPoint: function(w, h){
			return new googleApi.Point(w, h);
		},
		createOverlayView: function(w, h){
			return new googleApi.OverlayView();
		},
		createInfoWindow: function(optons){
			return new googleApi.InfoWindow(optons);
		},
		createMarker: function(options){
			if( typeof options.map == "undefined" ){ options.map = this.getInstance(); } 
			return new googleApi.Marker(options);
		},
		addListener: function(event, cb, instance){
			instance = instance || this.getInstance();
			return googleApi.event.addListener(instance, event, cb);
		},
		addDomListener: function(event, cb, element){
			return googleApi.event.addDomListener(element, event, cb);
		},
		initMap: initMap,
		changeLanguage: function(lang){
			if( !lang || lang == language ){ return false; }

			var oldScript = document.getElementById("google-maps-script");
			oldScript.parentNode.removeChild(oldScript);
			// console.log(google.maps);
			if( typeof google != "undefined" ){ delete google.maps; }

			language = lang;
			loadScript(lang);
		},
	}
})();

//events
// $("body").on("languageChange", function(e, lang){
// 	MapHandler.changeLanguage(lang);
// });
// 
module.exports = MapHandler;