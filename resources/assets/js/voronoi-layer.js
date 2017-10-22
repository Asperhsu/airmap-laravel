require("css/map-layer.css");

var d3 = require("d3");
var MapHandler = require("js/map-handler");

function VoronoiLayer(id, locations, fillColors){
	this.divID = id;
	this.map = MapHandler.getInstance();
	this.siteLocations = locations;
	this.fillColors = fillColors;
	this.setMap(this.map);
};

VoronoiLayer.prototype = MapHandler.createOverlayView();

VoronoiLayer.prototype.onAdd = function() {
 	var layer = d3.select(this.getPanes().overlayLayer)
 				  .append("div").attr("class", "voronoi-layer").attr("id", this.divID);
 	this.div = layer.node();
	var svg = layer.append("svg");
	this.svgoverlay = svg.append("g");
};
VoronoiLayer.prototype.googleMapProjection = function(coordinates) {
	var overlayProjection = this.getProjection();
	var googleCoordinates = new google.maps.LatLng(coordinates[0], coordinates[1]);
	var pixelCoordinates = overlayProjection.fromLatLngToDivPixel(googleCoordinates);
	var svgHalfDimention = 4000;
	return [pixelCoordinates.x + svgHalfDimention, pixelCoordinates.y + svgHalfDimention];
};
VoronoiLayer.prototype.draw = function() {
	var sitePositions = [];
	this.siteLocations.forEach(function(d) {		
		sitePositions.push(this.googleMapProjection(d) );
	}.bind(this));
	
	var sitePolygons = d3.geom.voronoi(sitePositions);
	var voronoiPathAttr = {
		fill: function(d, i){ return this.fillColors[i] || "none"; }.bind(this),
		d: function(d, i){ 
			//boundary.clip( d3.geom.polygon(sitePolygons[i]) );
			if( !sitePolygons[i] ){ return; }
			return "M" + sitePolygons[i].join("L") + "Z"
		},
	};

	this.svgoverlay.selectAll("path")
		.data(this.siteLocations)
		.attr(voronoiPathAttr)
		.enter()
		.append("svg:path")
		.attr("class", "cell")
		.attr(voronoiPathAttr);
};
VoronoiLayer.prototype.onRemove = function() {
	this.div.parentNode.removeChild(this.div);
};
VoronoiLayer.prototype.toggle = function(flag) {
	if( !this.div ){ return false; }

	if( typeof flag == "undefined" ){
		flag = this.div.style.visibility === 'hidden' ? true : false;	//reverse
	}else{
		flag = !!flag;
	}

	this.div.style.visibility = flag ? 'visible' : 'hidden';
};
VoronoiLayer.prototype.getContainer = function() {
	return this.div;
};


module.exports = VoronoiLayer;