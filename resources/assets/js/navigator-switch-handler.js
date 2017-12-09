require("bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css");

var LANG = require("js/lang");

//set default switch setting
(function(){
	var defaultSwitchOptions = {
		inverse: true,
		size: 'small',
		onText: 'I',
		offText: 'O',
		onColor: 'success',
		offColor: 'danger',
		handleWidth: "35",
	};
	Object.keys(defaultSwitchOptions).map(function(key, index) {
		$.fn.bootstrapSwitch.defaults[key] = defaultSwitchOptions[key];
	});
})();
$(".bs-switch").bootstrapSwitch({state: false});

//language swich
$("#languageSwitch").bootstrapSwitch('state', LANG.getLang() == "zh-TW");
$("#languageSwitch").on('switchChange.bootstrapSwitch', function(event, state) {
	var language = state ? 'zh-TW' : 'en-US';
	LANG.setLang(language).translateApp();			
});

//navigator layer switch
(function(){
	var layerToggleOptions = {
		state: false,
		size: 'mini',
		handleWidth: "15",
		onSwitchChange: function(e, state){
			var name = $(e.target).parents(".list-group-item").data('name');
			$("body").trigger("toggleLayer", [name, state]);
		}
	};
	Object.keys(layerToggleOptions).map(function(key, index) {
		$(".layerToggle").bootstrapSwitch(key, layerToggleOptions[key]);
	});
})();

//analysis toggle
(function(){
	var layerToggleOptions = {
		state: false,
		size: 'mini',
		handleWidth: "15",
		onSwitchChange: function(e, state){
			var actives = [];
			$(".bs-switch.statusToggle").each(function(){
				var status = $(this).data('status');
				var state = $(this).bootstrapSwitch('state');
				if(state){ actives.push(status); }
			});

			$("body").trigger("filterStatus", [actives]);
		}
	};
	Object.keys(layerToggleOptions).map(function(key, index) {
		$(".bs-switch.statusToggle").bootstrapSwitch(key, layerToggleOptions[key]);
	});
})();

//prevent browser cache
$(".layerToggle.siteLayer").bootstrapSwitch('state', true);
$(".bs-switch.statusToggle").bootstrapSwitch('state', true);


// siteLayer voronoi
$(".bs-switch.siteVoronoi").on('switchChange.bootstrapSwitch', function(event, state){
	var siteVoronoiLayer = $(this).data('layer');
	if( !siteVoronoiLayer ){
		var siteTool = require("js/site-tool");
		var VoronoiLayer = require("js/voronoi-layer");

		var voronoi = siteTool.getVoronoiData();
		var siteVoronoiLayer = new VoronoiLayer('siteVoronoi', voronoi['locations'], voronoi['colors']);
		$(this).data('layer', siteVoronoiLayer);

		setTimeout(function(){
			$("#siteVoronoi").fadeToggle('fast');
		}, 500);	
	}
		
	state ? $("#siteVoronoi").fadeIn('fast') : $("#siteVoronoi").fadeOut('fast');
});

// windLayer
$(".layerToggle.windLayer").on('switchChange.bootstrapSwitch', function (event, state) {
	var WindLayer = require("js/wind-layer");
	WindLayer.boot();
	state ? WindLayer.enable() : WindLayer.disable();
});

//emission layer
$(".bs-switch.emissionSites").on('switchChange.bootstrapSwitch', function(event, state){
	var EmissionLayer = require("js/emission-layer");
	if(state){
		EmissionLayer.boot().show();
	}else{
		EmissionLayer.hide();
	}
});
$(".bs-switch.emissionVoronoiLayer").on('switchChange.bootstrapSwitch', function(event, state){
	var container = "emissionVoronoi";
	var emissionVoronoiLayer = $(this).data('layer');

	if(state && !emissionVoronoiLayer){
		$(".bs-switch.emissionSites").bootstrapSwitch('state', true, false);
		setTimeout(function(){
			$("#emissionVoronoi").fadeIn(500);
		}, 500);	
	}
	state ? $("#"+container).fadeIn('fast') : $("#"+container).fadeOut('fast');	
});

//SatelliteLayer
$(".bs-switch.satelliteLayer").on('switchChange.bootstrapSwitch', function(event, state){
	var satelliteLayer = $(this).data("layer");
	if( !satelliteLayer ){
		var googleMapsApi = MapHandler.getApi();
		var bounds = new googleMapsApi.LatLngBounds(
			new googleMapsApi.LatLng(-1.5, 102.0-8.8),
			new googleMapsApi.LatLng(47.5, 155-8.8)
		);
		var srcImage = 'http://opendata.cwb.gov.tw/opendata/MSC/O-B0032-002.jpg';

		var SatelliteLayer = require("js/satellite-layer");
		var satelliteLayer = new SatelliteLayer("satelliteLayer", bounds, srcImage);
		$(this).data("layer", satelliteLayer);
	}

	state ? $("#satelliteLayer").fadeIn('fast') : $("#satelliteLayer").fadeOut('fast');
});