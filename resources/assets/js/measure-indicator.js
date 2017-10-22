require("css/measure-Indicator.css");
var Cookies = require("js.cookie");

var Indicator = {
	levelIndicatorContainerID: '#indicatorLevel',
	presentType: 'PM2.5',
	types: ['PM2.5', 'AQI', 'PM2.5_NASA', 'Temperature', 'Humidity'],
	units: {
		'PM2.5': 'μg/m3',
		'AQI': '',
		'PM2.5_NASA': 'μg/m3',
		'Temperature': '&#8451;',
		'Humidity': '%',
	},
	colors: {
		'AQI': {	//AQI
			15: '#00FF00',
			35: '#FFFF00',
			54: '#FF7E00',
			150: '#FF0000',
			250: '#800080',
			300: '#7E0023',
		},
		'PM2.5': {
			11: '#9CFF9C',
			23: '#31FF00',
			35: '#31CF00',
			41: '#FFFF00',
			47: '#FFCF00',
			53: '#FF9A00',
			58: '#FF6464',
			64: '#FF0000',
			70: '#990000',
			71: '#CE30FF',
		},
		'PM2.5_NASA': {
			0: '#0000CC',
			3: '#0133CC',
			5: '#0166FF',
			8: '#0099FF',
			10: '#32CBFE',
			13: '#65FE9A',
			15: '#99FF66',
			18: '#CCFF33',
			20: '#FFFF01',
			35: '#FF9933',
			50: '#FF3301',
			65: '#C90000',
			80: '#800000',
		},
		'Temperature': {
			5: '#6DB2CC',
			11: '#B9E6F6',
			15: '#4BAC66',
			21: '#A8D784',
			25: '#F0E389',
			29: '#F1B040',
			33: '#F55042',
			35: '#B6023C',
			37: '#9F66B5',
			40: '#752B8E',
		},
		'Humidity': {
			20: '#FAC090',
			40: '#76B531',
			60: '#B7DEE8',
			80: '#215968',
		}
	},
	displayName: {
		'PM2.5': 'PM2.5',
		'AQI': 'AQI',
		'PM2.5_NASA': 'PM2.5 NASA',
		'Temperature': '溫度',
		'Humidity': '濕度',
	},
	boot: function(){
		if( Cookies && Cookies.get('measureType')  ){
			var cookie = Cookies.get('measureType');

			if( this.types.indexOf(cookie) > -1 ){
				this.presentType = Cookies.get('measureType');				
			}
		}

		this.generateLevelBar();
		$("body").trigger("indicatorBoot");
	},
	getPresentType: function(){
		return this.presentType;
	},
	getTypes: function(){
		return this.types;
	},
	changeType: function(type){
		if( this.types.indexOf(type) > -1 ){ 
			this.presentType = type;
			this.generateLevelBar();

			Cookies.set('measureType', type);
			$("body").trigger("indicatorTypeChange", [type]);
		}
	},
	getLevels: function(type){
		if( this.colors[type] ){
			return this.colors[type];
		}
	},
	getLevelColor: function(level){
		var colors = this.colors[this.presentType];
		var lastColorMaxValue = Object.keys(colors).pop();
		for(var maxValue in colors){
			//console.log(level, maxValue);
			if( level <= maxValue ){
				return colors[maxValue];
			}

			//level greater lastone level
			if( level >= lastColorMaxValue){
				return colors[lastColorMaxValue];
			}

		}
	},
	generateLevelBar: function(){
		var type = this.presentType;
		var unit = this.units[type];

		var levels = '';
		for(var value in this.colors[type]){
			var color = this.colors[type][value];
			levels += '<div class="level" style="background-color: ' + color + ';">' + value + '</div>';
		}

		var html = [];
		
		html.push('<div class="title">');
		html.push('<div class="type">' + type + '</div>');
		html.push('<div class="unit">' + unit + '</div>');
		html.push('</div>');
		html.push('<div class="levels">');
		html.push(levels);
		html.push('</div>');
		
		$(this.levelIndicatorContainerID).html(html.join(''));
	}
}

module.exports = Indicator;