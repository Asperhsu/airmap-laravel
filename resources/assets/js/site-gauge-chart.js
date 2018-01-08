var d3gauge = require('d3-gauge');
require("d3-gauge/defaults/simple.css");
require("css/gauge.css");

var GaugeChart = {
	options: {
		XL: {
			size: 320,
			minorTicks: 5,
			majorTicks: 5,
			clazz: 'size-xl simple',
		},
		L: {
		    size: 220,
		    minorTicks: 5,
			majorTicks: 5,
			clazz: 'size-l simple',
		},
		M: {
		    size: 180,
		    minorTicks: 5,
			majorTicks: 5,
			clazz: 'size-m simple',
		},
		S: {
		    size: 90,
		    minorTicks: 5,
			majorTicks: 5,
			clazz: 'size-s simple',
		},
	},
	getConfig: function(userOptions){
		if( !userOptions['site'] || !userOptions['element'] || !userOptions['measureType'] ){ return false; }

		var gauge = {
			site: 			userOptions['site'],
			element: 		userOptions['element'],
			measureType: 	userOptions['measureType'],
			size: 			userOptions['size'],
			title: 			userOptions['title'] || userOptions['measureType'],
			fontStyle: 		userOptions['fontStyle'] || {},
			instance: 		null,
			timer: 			null,
		}

		return gauge;
	},
	draw: function(userOptions){
		var config = this.getConfig(userOptions);
		var data = this.getData(config);

		//clear content, prevent show previous value
		var $target = $(config.element);
		$target.html('');

		if( data === false ){
			var html = [
				'<div class="gauge-no-data">',
					'<span class="glyphicon glyphicon-question-sign"></span>',
					'&nbsp;No Data',
				'</div>',
			].join('');
			$target.html(html)
			return false;
		}

		var sizeSetting = this.getSizeSetting(config);
		var colorOptions = this.getColorOptions(config);
		var clazz = [sizeSetting.clazz, colorOptions.clazz].join(' ');
		var options = Object.assign({}, sizeSetting, colorOptions, {clazz: clazz} );

		config.instance = d3gauge($target[0], options);
		config.instance.write(data);

		return config;
	},
	getData: function(config){
		var value = config.site.getMeasure(config.measureType);
		return isNaN(value) ? false : value;
	},
	getSizeSetting: function(config){
		if(typeof this.options[config.size] !== "undefined"){
			return this.options[config.size];
		}

		var sizeDiff = {};

		//auto setting
		var dimention = Math.min($(config.element).width(), $(config.element).height());
		for(var sizeName in this.options){
			var diff = Math.abs(this.options[sizeName].size - dimention);
			sizeDiff[diff] = sizeName;
		}

		var minDiff = Math.min.apply(null, Object.keys(sizeDiff));
		return Object.assign({}, this.options[sizeDiff[minDiff]], {size: dimention});
	},
	getColorOptions: function(config){
		switch(config.measureType){
			case 'PM2.5':
				var min = 0;
				var max = 71;
				return {
					min: min,
					max: max,
					clazz: "pm25",
					label: config.measureType,
					zones: [
						{ from: min, 			to: 11/max, 		clazz: 'light-green-zone' },
						{ from: min+11/max, 	to: min+23/max, 	clazz: 'green-zone' },
						{ from: min+23/max, 	to: min+35/max, 	clazz: 'dark-green-zone' },
						{ from: min+35/max, 	to: min+41/max, 	clazz: 'yellow-zone' },
						{ from: min+41/max,		to: min+47/max, 	clazz: 'golden-zone' },
						{ from: min+47/max,		to: min+53/max, 	clazz: 'orange-zone' },
						{ from: min+53/max,		to: min+58/max, 	clazz: 'indian-red-zone' },
						{ from: min+58/max,		to: min+64/max, 	clazz: 'red-zone' },
						{ from: min+64/max,		to: min+70/max, 	clazz: 'brown-zone' },
						{ from: min+70/max, 	to: 1,				clazz: 'purple-zone' },
					],
				};
			case 'AQI':
				var min = 0;
				var max = 300;
				return {
					min: min,
					max: max,
					clazz: "AQI",
					label: config.measureType,
					zones: [
						{ from: min, 			to: 15/max, 		clazz: 'green-zone' },
						{ from: min+15/max, 	to: min+35/max, 	clazz: 'yellow-zone' },
						{ from: min+35/max, 	to: min+54/max, 	clazz: 'orange-zone' },
						{ from: min+54/max, 	to: min+150/max, 	clazz: 'red-zone' },
						{ from: min+150/max,	to: min+250/max, 	clazz: 'purple-zone' },
						{ from: min+250/max, 	to: 1,				clazz: 'brown-zone' },
					],
				};
			case 'Temperature':
				var min = 0;
				var max = 40;
				return {
					min: min,
					max: max,
					clazz: "",
					label: "Temp",
					zones: [
						{ from: min, 			to: 26/max, 		clazz: 'green-zone' },
						{ from: min+26/max, 	to: min+30/max, 	clazz: 'yellow-zone' },
						{ from: min+30/max, 	to: 1, 				clazz: 'red-zone' },
					],
				};
			case 'Humidity':
				var min = 0;
				var max = 100;
				return {
					min: min,
					max: max,
					clazz: "",
					label: "RH",
					zones: [
						{ from: min, 			to: 60/max, 		clazz: 'green-zone' },
						{ from: min+60/max, 	to: min+80/max, 	clazz: 'yellow-zone' },
						{ from: min+80/max, 	to: 1, 				clazz: 'red-zone' },
					],
				};
		}
	},
}

module.exports = GaugeChart;