var CanvasLayer = require('js/vendor/CanvasLayer');
var Windy = require('js/vendor/windy');

var WindLayer = {
	state: {
		isInitiated: false,
		isLayerEnable: false,
		isUserEnable: false,
		timer: null,
	},
	instance: {
		windy: null,
		context: null,
		map: MapHandler.getInstance(),
	},
	property: {
		fillOpacity: 0.6,
		moveSpeed: 1,
		windyData: {},
		gfsJson: "/json/gfs.json",
	},	
	boot: function(){
		MapHandler.addListener('bounds_changed', function(){
			this.clear();
		}.bind(this));

		MapHandler.addListener('zoom_changed', function(){
			var zoom = this.instance.map.getZoom();
			if( zoom <= 7 ){ this.changeMoveSpeed(1); return; }
			if( zoom <= 8 ){ this.changeMoveSpeed(2); return; }
			if( zoom <= 10 ){ this.changeMoveSpeed(3); return; }
			if( zoom <= 11 ){ this.changeMoveSpeed(4); return; }
			if( zoom <= 12 ){ this.changeMoveSpeed(5); return; }
			if( zoom <= 14 ){ this.changeMoveSpeed(6); return; }
			if( zoom <= 15 ){ this.changeMoveSpeed(7); return; }
			if( zoom <= 16 ){ this.changeMoveSpeed(8); return; }
			if( zoom <= 17 ){ this.changeMoveSpeed(9); return; }
			if( zoom >= 17 ){ this.changeMoveSpeed(10); return; }
		}.bind(this));	

		return this;
	},
	enable: function(){
		this.state.isUserEnable = true;
		this.start();

		return this;
	},
	disable: function(){
		this.state.isUserEnable = false;
		this.stop();

		return this;
	},
	init: function(){
		if( this.state.isInitiated ){ return; }

		this.toggleLoading(true);
		$.getJSON(this.property.gfsJson)
			//initial wind js
			.success(function(result){
				var canvasLayerOptions = {
					map: this.instance.map,
					animate: false,
					updateHandler: function(){
						this.redraw();
					}.bind(this)
				};
				var canvasLayer = new CanvasLayer(canvasLayerOptions);
				
				this.property.windyData = result;
				this.state.isLayerEnable = true;
				this.state.isInitiated = true;	
				this.instance.context = canvasLayer.canvas.getContext('2d');
				this.instance.windy = new Windy({canvas: canvasLayer.canvas, data: result});					
			}.bind(this))
			.success(function(result){
				var dateTime = moment(result[0]['header']['refTime']).format("YYYY-MM-DD HH:mm");
				$("body").trigger("wind_changeUpdateString", [dateTime]);
			});
	},
	toggleLoading: function(state){
		$("body").trigger("wind_loading", [state]);
	},
	getFillOpacity: function(){
		return this.property.fillOpacity;
	},
	changeFillOpacity: function(opacity){
		if(!opacity){ return false; }
		this.property.fillOpacity = opacity;
		
		if(this.state.isLayerEnable){
			this.instance.windy.setFillOpacity(opacity);
			$("body").trigger("wind_lineOpacity", [this.getFillOpacity()]);
		}
	},
	getMoveSpeed: function(){
		return Math.log(this.property.moveSpeed) / Math.log(2) +1;
	},
	changeMoveSpeed(speed){
		if(!speed || speed <= 0){ return false; }

		this.property.moveSpeed = Math.pow(2, speed-1);
		if(this.state.isLayerEnable){
			this.instance.windy.setMovingSpeed(this.property.moveSpeed);
			$("body").trigger("wind_movingSpeed", [this.getMoveSpeed()]);
		}
	},
	redraw: function(overlay, params) {
		if(!this.state.isLayerEnable || !this.state.isUserEnable ){ return; }

		if( this.state.timer ){ clearTimeout(this.state.timer); }
		
		var $map = $(MapHandler.getContainer());
		this.state.timer = setTimeout(function() {
			var bounds = this.instance.map.getBounds();
			var map_size_x = $map.width();
			var map_size_y = $map.height();

			this.instance.windy.setFillOpacity(this.property.fillOpacity);
			this.instance.windy.setMovingSpeed(this.property.moveSpeed);
			this.instance.windy.params.moveSpeed = this.property.moveSpeed;
			this.instance.windy.start(
				[
					[0,0], 
					[map_size_x, map_size_y]
				], 
				map_size_x, map_size_y, 
				[
					[bounds.getSouthWest().lng(), bounds.getSouthWest().lat() ],
					[bounds.getNorthEast().lng(), bounds.getNorthEast().lat() ]
				]
			);
			this.toggleLoading(false);				
		}.bind(this), 700)
	},
	start: function(){
		if( this.instance.map.getZoom() <= 4 ){ return; }

		this.toggleLoading(true);
		this.state.isLayerEnable = true;
		if(this.state.isInitiated){
			this.redraw();
		}else{
			this.init();
		}
	},
	stop: function(){
		this.state.isLayerEnable = false;
		this.clear();
	},
	clear: function(){
		if( this.instance.windy ){
			this.instance.windy.stop();
			this.instance.context.clearRect(0,0,3000, 3000);
		}
	},
};

$("body").on("wind_lineOpacity", function(e, value){
	if(value == WindLayer.getFillOpacity()){ return; }
	WindLayer.changeFillOpacity(value);
});
$("body").on("wind_movingSpeed", function(e, value){
	if(value == WindLayer.getMoveSpeed()){ return; }
	WindLayer.changeMoveSpeed(value);
});

module.exports = WindLayer;