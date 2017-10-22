var VoronoiLayer = require("js/voronoi-layer");

var layer;
var booted = false;
var markers = [];

exports.show = function(){
	var map = MapHandler.getInstance();
	markers.map(function(marker){
		marker.setMap(map);
	});

	return this;
}

exports.hide = function(){
	markers.map(function(marker){
		marker.setMap(null);
	});

	return this;
}

exports.boot = function(){
	if(booted){ return this; }

	var locations = [];
	var emissionData = require("json/emission.json");
	var image = {
		url: 'https://i.imgur.com/Q7nqYMX.png',
		size: 		MapHandler.createSize(30, 30),
		origin: 	MapHandler.createPoint(0, 0),
		anchor:		MapHandler.createPoint(0, 30),
		scaledSize: MapHandler.createSize(20, 20),
	};

	emissionData.map(function(site){				
		locations.push([site.latitude, site.longitude]);
		var marker = MapHandler.createMarker({
			title: site.name,
			icon: image,
			position: MapHandler.createLatLng(site.latitude, site.longitude),
		});
		markers.push(marker);
	});

	layer = new VoronoiLayer("emissionVoronoi", locations, {}); 

	booted = true;

	return this;
}