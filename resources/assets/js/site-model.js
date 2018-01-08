var Helper 		= require("js/helper");
var Indicator 	= require("js/measure-indicator");
var SVGTool 	= require("js/svg-tool");

function Site(data){
	this.property = {};
	this.marker = null;

	this.setProperties(data);
}

/**
 * test site is valid
 * @return {Boolean} [description]
 */
Site.prototype.isValid = function(){
	var item = this.property;

	//time filter
	// if( item.Data && moment().diff(moment(item.Data.Create_at), 'minutes') > 30 ){
	// 	return false;
	// }

	//location filter
	if( !item.LatLng || !item.LatLng.lng || !item.LatLng.lat ){
		return false;
	}

	return true;
};

Site.prototype.match = function(search){
	if( !search || !search.length ){ return false; }
	search = search.toLowerCase();
	var matched = [];

	if( this.getProperty('SiteName').toLowerCase().indexOf(search) > -1 ){
		matched.push(this.getProperty('SiteName'));
	}

	if( this.getProperty('SiteGroup').toLowerCase().indexOf(search) > -1 ){
		matched.push(this.getProperty('SiteGroup'));
	}

	if( this.getProperty('uniqueKey').toLowerCase().indexOf(search) > -1 ){
		var value = this.getProperty('uniqueKey');
		if( matched.indexOf(value) == -1 ){
			matched.push(value);
		}
	}

	if( this.getProperty('Maker').toLowerCase().indexOf(search) > -1 ){
		matched.push(this.getProperty('Maker'));
	}

	return matched.length ? matched : false;
};


/**
 * ===================
 * Property
 * ===================
 */

Site.prototype.setProperties = function(item){
	if( !item || !item.Data || !item.Data.Create_at ){
		return false;
	}

	this.property = item;
};

Site.prototype.getProperty = function(key){
	return Helper.getObjectValue(this.property, key);
};


/**
 * =============================
 * Shotcut to retrive property
 * =============================
 */

Site.prototype.getMeasure = function(measureType){
	if(['PM2.5', 'PM2.5_NASA', 'AQI'].indexOf(measureType) > -1 ){
		measureType = 'Dust2_5';
	}

	var measureValue = this.getProperty('Data.'+measureType);
	return (measureValue === null || isNaN(measureValue)) ? null : Math.round(measureValue);
};

Site.prototype.getIdentity = function(){
	return this.getProperty('uniqueKey');
}

Site.prototype.getTitle = function(){
	return '[' + this.getProperty('SiteGroup') + '] ' + this.getProperty('SiteName');
}

Site.prototype.getMeasureColor = function(){
	var measureType = Indicator.getPresentType();
	var value = this.getMeasure(measureType);
	return value != null ? Indicator.getLevelColor(value) : 'transparent';
}

Site.prototype.getPosition = function(){
	var LatLng = this.getProperty('LatLng');
	if( LatLng && LatLng.lat && LatLng.lng ){
		if(MapHandler.getApi()){
			return MapHandler.createLatLng(LatLng.lat, LatLng.lng);
		}else{
			return { lat: +LatLng.lat, lng: +LatLng.lng };
		}
	}
	return null;
}


/**
 * =====================
 * Remote Resource
 * =====================
 */

Site.prototype.fetchLastest = function(group, id, includeRaw=false){
	var urlTemplate = "/json/query-lastest?group={{group}}&id={{id}}";
	if(includeRaw){ urlTemplate = urlTemplate+"&raw=1"; }

	var url = urlTemplate.replace('{{group}}', group).replace('{{id}}', id);
	return new Promise((resolve, reject) => {
		$.getJSON(url).then((data) => {
			if(Object.keys(data).length){
				this.setProperties(data);
				resolve(this);
			}else{
				resolve(null);
			}
		}, (err, exception) => {
			var errorText = 'Load Lastest Record Error: ';
			errorText += Helper.getAjaxErrorText(err, exception);

			reject(errorText);
		});
	});
};

Site.prototype.fetchHistory = function(offsetHours){
	var group 	= this.getProperty('SiteGroup');
	var id 		= this.getProperty('uniqueKey');
	var end 	= moment().unix();
	var start 	= moment.unix(end).subtract(parseInt(offsetHours), 'hours').unix();

	if(!group || !id || !start ){
		return false;
	}

	var urlTemplate = "/json/query-history?group={{group}}&id={{id}}&start={{start}}&end={{end}}";
	var url = urlTemplate.replace('{{group}}', group).replace('{{id}}', id)
						 .replace('{{start}}', start).replace('{{end}}', end);

	return new Promise((resolve, reject) => {
		$.getJSON(url).then((history) => {
			var labels = [];
			var datasets = [];
			for(var index in history){
				var data = history[index];

				if(index == 'isotimes'){
					data.map(isoString => {
						var label = new Date(isoString);
						labels.push(label);
					});
					continue;
				}

				datasets.push({
					label: index,
					data: history[index],
				});
			}

			resolve({
				labels: labels,
				datasets: datasets,
			});
		}, (err, exception) => {
			var errorText = 'Load History Error: ';
			errorText += Helper.getAjaxErrorText(err, exception);

			reject(errorText);
		});
	});
};


/**
 * =======================
 * Marker
 * =======================
 */

Site.prototype.createMarker = function(options){
	options = options || {};
	var position = this.getPosition();
	if( !position ){
		console.log(this.getProperty('SiteName') + " position not avaliable");
		return false;
	}

	var option = {
		'title': this.getTitle(),
		'position': position,
		'map': MapHandler.getInstance(),
	};
	delete options.onMap;

	//get icon
	var icon = this.getIcon();
	if(icon){ option['icon'] = icon; }

	this.marker = MapHandler.createMarker( $.extend({}, option, options) );

	MapHandler.addListener('click', function(){
		this.openInfoWindow();
	}.bind(this), this.marker);
}

Site.prototype.getMarker = function(){
	return this.marker;
}

Site.prototype.toggleMarker = function(flag){
	if( !this.marker ){ return false; }

	if( typeof flag == "undefined" ){
		flag = this.marker.getMap() == null ? true : false; //reverse
	}else{
		flag = !!flag;
	}
	var map = MapHandler.getInstance();
	this.marker.setVisible(flag);
}

Site.prototype.updateMarkerColor = function(){
	var marker = this.getMarker();
	if( marker ){
		marker.setIcon(this.getIcon());
	}
}

Site.prototype.getIcon = function(){
	return this.getIconSVG();
}

Site.prototype.getIconSVG = function(){
	var color = '#006699';
	var text = '';

	if( typeof Indicator !== "undefined" ){
		var measureType = Indicator.getPresentType();
        text = this.getMeasure(measureType);
        if (isNaN(text)) { text = ''; }
		color = this.getMeasureColor();
	}

	var url = SVGTool.getCircleUrl(color, text);
	var status = this.getProperty('Analysis.status');
	if(status){
		if(status.indexOf('indoor') > -1){ url = SVGTool.getHomeUrl(color, text); }
		if(status.indexOf('longterm-pollution') > -1){ url = SVGTool.getFactoryUrl(color, text); }
		if(status.indexOf('shortterm-pollution') > -1){ url = SVGTool.getCloudUrl(color, text); }
	}

	return {
		anchor: MapHandler.createPoint(15, 15),
		url: url,
		value: text,
	};
}

Site.prototype.getIconImage = function(){
	var color = '';
	var text = '';
	if( typeof Indicator !== "undefined" ){
		var measureType = Indicator.getPresentType();
		var measureValue = this.getMeasure(measureType);
		text = measureValue === null ? '' : Math.round(measureValue);
		color = this.getMeasureColor();
		if(color == "transparent"){ color = ''; }
	}
	var url = [
		"/image/markerIcon/",
		color.replace('#', ''),
		// "/" + text
	].join('');
	return {
		url: url,
		scaledSize: MapHandler.createSize(30, 30),
		value: text,
	}
}


/**
 * =======================
 * InfoWindow
 * =======================
 */

Site.prototype.openInfoWindow = function(){
	var InfoWindowLayer = require("js/map-infowindow-layer");
	InfoWindowLayer.putOn(this);
}


module.exports = Site;