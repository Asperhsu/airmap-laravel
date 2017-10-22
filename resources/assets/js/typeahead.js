require("css/typeahead.css");
require("js/bootstrap3-typeahead.js");

var siteTool 			= require("js/site-tool");
var MapApi 				= MapHandler.getApi();
var MapInstance 		= MapHandler.getInstance();
var AutocompleteService = new MapApi.places.AutocompleteService();
var Geocoder 			= new MapApi.Geocoder;

function getPlaceLatLng(query){
	return new Promise((resolve, reject) => {
		var placeIDs = [];
		AutocompleteService.getQueryPredictions({ input: query }, function(predictions, status){
			if(status != MapApi.places.PlacesServiceStatus.OK) { return placeIDs; }

			predictions.forEach(function(prediction){
				placeIDs.push({
					placeID: prediction.place_id,
					name: prediction.description,
				})
			});
			resolve(placeIDs);
		});
	});
}

function placeToLatLng(placeID){
	return new Promise((resolve, reject) => {
		Geocoder.geocode({'placeId': placeID}, function(results, status) {
			if (status !== google.maps.GeocoderStatus.OK || !results[0] ) { return; }
			resolve(results[0].geometry.location);
		});
	});
}


var $input = $("#typeahead");
var cacheQuery = null;
var cachePlaceLatLng = [];
$input.typeahead({
	source: function(query, callback){
		var sources = [];

		//sites info
		var sites = siteTool.search(query);
		sources = sources.concat(sites);

		if( cacheQuery === null || (query.indexOf(cacheQuery) == -1 && cacheQuery.indexOf(query) == -1) ){
			//query prefix is same, don't fetch placeIDs
			getPlaceLatLng(query).then(placeIDs => {
				cachePlaceLatLng = placeIDs;
				sources = sources.concat(placeIDs);
				callback(sources);
			});
		}else{
			sources = sources.concat(cachePlaceLatLng);
			callback(sources);
		}
		cacheQuery = query;
	},
	items: 'all',
	autoSelect: true,
	fitToElement: true,
	minLength: 3,
	highlighter: function(displayText, item) {
		var iconClass = "";
		if(item.instance){ iconClass = "glyphicon-bookmark"; }
		if(item.placeID){ iconClass = "glyphicon-map-marker"; }

		var query = this.query;
		var reEscQuery = query.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		var reQuery = new RegExp('(' + reEscQuery + ')', "gi");
		var text = displayText.replace(reQuery, '<strong>$1</strong>');

		return '<span class="glyphicon ' + iconClass + '"></span> ' + text;
	},
});
$input.change(function() {
	var current = $input.typeahead("getActive");
	if (!current) { return false; }

	if( current.instance ){
		var position = current.instance.getPosition();
		MapInstance.setCenter(position);
		MapInstance.setZoom(16);
	}

	if( current.placeID ){
		placeToLatLng(current.placeID).then(position => {
			MapInstance.setCenter(position);
			MapInstance.setZoom(16);
		});
	}
});