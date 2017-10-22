require("css/map-infowindow.css");

var LANG = require("js/lang");
var GaugeChart = require("js/site-gauge-chart");
var Indicator = require("js/measure-indicator");

function InfoWindowLayer(){
	this.containerID = 'iw-container';	
	this.div = null;
	this.position = null;
	this.displayTime = 0;
	this.map = MapHandler.getInstance();
	this.setMap(this.map);
};

InfoWindowLayer.prototype = MapHandler.createOverlayView();

InfoWindowLayer.prototype.setSite = function(Site){
	this.Site = Site;
	this.position = Site.getMarker().getPosition();

	var indepPageLink = "/site#" + Site.getProperty('SiteGroup') + '$' + Site.getIdentity();
	var widgetPageLink = "/widget/create/" + Site.getProperty('SiteGroup') + '$' + Site.getIdentity();
	var $container = $("#" + this.containerID);
	$container.find(".iw-name").text( Site.getTitle() );
	$container.find(".indep-page").attr('href', indepPageLink );
	$container.find(".widget-page").attr('href', widgetPageLink );
	
	//sinica ranking
	var ranking = Site.getProperty('Analysis.ranking');
	var $ranking = $container.find(".ranking").html('');
	if(ranking !== null){
		var html = [];
		var template = '<span class="glyphicon glyphicon-{{icon}}"></span>';
		for(var i=1; i<=5; i++){
			html.push(template.replace('{{icon}}', i<=ranking ? 'star' : 'star-empty'));
		}
		$ranking.html(html.join(''));
	}

	//update at
	var $updateAt = $container.find(".update-at");
	var CreateAt = moment(Site.getProperty('Data.Create_at'));
	$updateAt.attr('title', CreateAt.format('YYYY-MM-DD HH:mm:ss'))
			 .find(".time").text(CreateAt.toNow(true));
}

InfoWindowLayer.prototype.onAdd = function() {
	var html = [
		'<div id="' + this.containerID + '" >',
			'<div class="arrow"></div>',
			'<div class="iw-header">',
				'<div class="ranking" data-lang="ranking" title="ranking"></div>',
				'<div class="update-at">Updated <span class="time"></span> ago.</div>',
			'</div>',
			'<div class="iw-content">',
				'<div class="main-garge garge-background"></div>',
				'<div class="sub-garge">',
					'<div class="sub-garge-top garge-background"></div>',
					'<div class="sub-garge-bottom garge-background"></div>',
				'</div>',
			'</div>',
			'<div class="iw-footer">',
				'<div class="iw-name"></div>',
				'<div class="iw-link">',
					'<a href="" target="_blank" class="widget-page" title="widget">',
						'<span class="glyphicon glyphicon-dashboard"></span>',
					'</a>',
					'<a class="line-chart" data-lang="historyChart" title="historyChart">',
						'<span class="glyphicon glyphicon-stats"></span>',
					'</a>',
					'<a href="" target="_blank" class="indep-page" data-lang="independentPage" title="independentPage">',
						'<span class="glyphicon glyphicon-bookmark"></span>',
					'</a>',
				'</div>',
			'</div>',
		'</div>'
	].join('');

	this.div = $(html)[0];

	var self = this;
	var $body = $("body");

	//click outside to close navigator
	$body.click(function(e){
		if( $(e.target).parents(MapHandler.getContainer()).length ){
			var time = new Date().getTime();
			var isChildren = $.contains('#iw-container', e.target);
			if( !isChildren && (time - self.displayTime) > 1000 ){	//open 1 secs can remove, fix for event racing
				self.remove();
				$body.trigger('infoWindowClose', [self.Site]);
			}
		}
	});

	// Add the element to the "overlayLayer" pane.
	var panes = this.getPanes();
	panes.overlayMouseTarget.style.zIndex = 200;
	panes.overlayMouseTarget.appendChild(this.div);

	function cancelEvent(e) {
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	}
	
	google.maps.event.addDomListener(document.querySelector('.line-chart'), 'click', function(e){
		var $el =  $(e.target);		
		var isA = $el.is('a') && $el.hasClass("line-chart");
		var parentIsA = $el.parents('a').length && $el.parents('a').hasClass("line-chart");
		if( isA || parentIsA ){ 
			$body.trigger("openNavigator", ['siteChart']);
			$body.trigger("showHistoryChart", [self.Site]);
		}
		cancelEvent(e);
	});
	google.maps.event.addDomListener(this.div, 'mousedown', cancelEvent); //cancels drag/click
	google.maps.event.addDomListener(this.div, 'click', cancelEvent);       //cancels click
	google.maps.event.addDomListener(this.div, 'dblclick', cancelEvent);    //cancels double click
	google.maps.event.addDomListener(this.div, 'contextmenu', cancelEvent);  //cancels double right click 
};
InfoWindowLayer.prototype.draw = function() {
	if(!this.position){ return false; }

	var overlayProjection = this.getProjection();
	var point = overlayProjection.fromLatLngToDivPixel(this.position);

	var arrowHeight = 25;
	var $div = $('#' + this.containerID);
	var width = $div.width();
	var height = $div.height();

	var div = this.div;
	div.style.left = (point.x - width/2)+ 'px';
	div.style.top = (point.y - height - arrowHeight) + 'px';
	LANG.translateApp($div);
};
InfoWindowLayer.prototype.onRemove = function() {
	this.div.parentNode.removeChild(this.div);
	this.div = null;
};
InfoWindowLayer.prototype.toggle = function(flag) {
	if( !this.div ){ return false; }

	if( typeof flag == "undefined" ){
		flag = this.div.style.visibility === 'hidden' ? true : false;	//reverse
	}else{
		flag = !!flag;
	}
	this.div.style.visibility = flag ? 'visible' : 'hidden';

	this.displayTime = new Date().getTime();
};

InfoWindowLayer.prototype.putOn = function(Site){
	this.setSite(Site);
	this.toggle(true);
	this.draw();
	this.map.setCenter(this.position);
	this.map.panBy(0, -100);
	this.initGauge();

	$("body").trigger('infoWindowReady', [this.Site]);
}

InfoWindowLayer.prototype.remove = function(){
	this.toggle(false);

	$("body").trigger('infoWindowClose', [this.Site]);
}

InfoWindowLayer.prototype.initGauge = function(){
	var IndicatorType = Indicator.getPresentType();
	var pm25Type = ['PM2.5', 'AQI'].indexOf(IndicatorType) > -1 ? IndicatorType : "PM2.5";

	var chart = {
		main: {
			element: "#iw-container .main-garge",
			size: 'M',
			title: 'PM 2.5',
			site: this.Site,
			measureType: pm25Type,
		},
		subTop: {
			element: "#iw-container .sub-garge-top",
			size: 'S',
			title: 'Temp',
			site: this.Site,
			measureType: 'Temperature',
		},
		subBottom: {
			element: "#iw-container .sub-garge-bottom",
			size: 'S',
			title: 'RH',
			site: this.Site,
			measureType: 'Humidity',
		}
	};

	GaugeChart.draw(chart.main);
	GaugeChart.draw(chart.subTop);
	GaugeChart.draw(chart.subBottom);
}

module.exports = new InfoWindowLayer();