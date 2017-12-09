require("css/common.css");
require("css/site.css");

var SiteModel = require("js/site-model");
var GaugeChart = require("js/site-gauge-chart");
var SiteHistoryChart = require("js/site-history-chart");
var siteHelper = {
	getInfo: function(hash){
		if( !hash.length ){ return null; }

		var identity = decodeURIComponent(hash).split('$');
		return {
			group: 	identity[0],
			id: 	identity[1],
		}
	},
	fetchLastest: function(group, id){
		var instance = new SiteModel();
		return instance.fetchLastest(group, id, true);
	},
};

var siteInfo = siteHelper.getInfo(window.location.hash.substr(1));
if(siteInfo && siteInfo.group && siteInfo.id){
	loadLastest(true);
}else{
	$("#container").hide();
	$("#no-content").fadeIn();
}

$("#sidebar li").click(function(){
	$(this).find('a').click();
});
$("#sidebar li > a").click(function(){
	var target = this.hash,
	$target = $(target);
	$('html, body').stop().animate({
		'scrollTop': $target.offset().top-60
	}, 900, 'swing');
	return false;
});

function loadLastest(firstTime){
	$("#loading").show().find(".msg").text("Loading Site Lastest Record");
	siteHelper.fetchLastest(siteInfo.group, siteInfo.id).then(function(Site){
		if( Site === null ){
			throw "Site Not Found";
		}

		$("#container").fadeIn();
		$("#no-content").hide();
		$("#navbar")
			.find(".site-group").text(Site.getProperty('SiteGroup')).end()
			.find(".site-name").text(Site.getProperty('SiteName'));

		initGarge(Site);
		initSuggestion(Site);
		initCreateAtText(Site);
		initDetailTable(Site);

		if(firstTime){			
			initChartControl(Site);
			initLocation(Site);
		}

		$("#loading").hide();
		setTimeout(loadLastest, 5* 60 * 1000);	//5 min reload
	}).catch(errorText => {
		$("#error-msg").find(".msg").text(errorText).end().show();
		$("#container").hide();
		$("#loading").hide();
	});
}


function initGarge(Site){
	if(!Site){ return false; }
	var runningConfigs = [];

	var configs = [
		{
			element: "#gauge-pm25",
			title: 'PM 2.5',
			measureType: 'PM2.5',
			site: Site,
			fontStyle: {
				color: '#555',
				fontSize: '20',
			}
		},
		{
			element: "#gauge-temp",
			title: 'Temp',
			measureType: 'Temperature',
			site: Site,
			fontStyle: {
				color: '#555',
			}
		},
		{
			element: "#gauge-humi",
			title: 'RH',
			measureType: 'Humidity',
			site: Site,
			fontStyle: {
				color: '#555',
			}
		}
	];

	configs.map(function(config){
		var gaugeConfig = GaugeChart.draw(config);
		if(gaugeConfig){
			runningConfigs.push(gaugeConfig);

			$(window).resize(function(){
				GaugeChart.draw(gaugeConfig);
			});
		}else{
			$(config.element).html('').addClass('no-data');
		}
	});
}

function initSuggestion(Site){
	var value = Site.getMeasure('PM2.5');
	$("#gauge .suggestion div[data-range-min]").each(function(){
		var min = $(this).data('range-min');
		var max = $(this).data('range-max');
		var color = $(this).data('color');

		if(value >= min && value <= max){
			$(this).show();
			$(this).parents('div[class$="-human"]').find('.label-suggestion').css('background-color', color);			
		}else{
			$(this).hide();
		}
	});
}

function initCreateAtText(Site){
	var creatAt = Site.getProperty('Data.Create_at');
	var dd = moment.utc(creatAt);
	var humanTime = dd.toNow(true);
	var localTime = dd.local().format('YYYY-MM-DD HH:mm:ss');

	$("#gauge .create-at")
		.find(".human-timestring .time").text(humanTime).end()
		.find(".utc-timestring").attr('title', creatAt).end()
		.find(".local-timestring").attr('title', localTime).end();
}

function initChartControl(Site){
	var $historyContainer = $("#history .body");
	var $loading = $historyContainer.find(".loading").hide();
	var $alert = $historyContainer.find(".loading-error").hide();
	var $chart = $("#site-history-chart");
	var $chartControl = $historyContainer.find(".chart-control");

	$chartControl.find(".btn[data-offset-hours]").click(function(){
		$(this).siblings().removeClass('active').end()
			   .addClass('active');

		$loading.show();

		var offsetHours = $(this).data('offset-hours');
		Site.fetchHistory(offsetHours).then(chartData => {
			SiteHistoryChart.start(chartData, {backgroundColor: '#FAFAFA'});
			$loading.hide();
		}).catch(errorText => {
			$alert.text(errorText).show();
			$chart.hide();
			$chartControl.hide();
			$loading.hide();
		});

	}).filter(":first").click();
}

function genTable(title, data){
	var tbody = '';
	for(var key in data){
		tbody += '<tr><th>' + key + '</th><td>' + data[key] + '</td></tr>';
	}

	return [
		'<div class="col-sm-12 col-md-6">',
			'<div class="panel panel-info">',
				'<div class="panel-heading">',
					title,
				'</div>',
				'<table class="table table-striped"><tbody>',
				tbody,
				'</tbody></table>',
			'</div>',
		'</div>'
	].join('');
}

function initLocation(Site){
	var position = Site.getPosition();
	var map = new google.maps.Map(document.getElementById('map'), {
		center: position,
		zoom: 16
	});

	new google.maps.Marker({
		position: position,
		map: map,
	});
}

var isNullMsg = function(value, msg){
	return value === null ? msg : value;
}

function initDetailTable(Site){
	var $detail = $("#detail .body .row");
	var obj = {
		ID:     			Site.getProperty('uniqueKey'),
		SiteGroup: 			Site.getProperty('SiteGroup'),
		SiteName: 			Site.getProperty('SiteName'),
		Maker: 				Site.getProperty('Maker'),
		Lat: 				Site.getProperty('LatLng.lat'),
		Lng: 				Site.getProperty('LatLng.lng'),
		ReliableRanking: 	isNullMsg(Site.getProperty('Analysis.ranking'), 'no ranking'),
		SupposeStatus: 		isNullMsg(Site.getProperty('Analysis.status'), 'no status'),
	};

	$detail.html('');
	$detail.append( genTable('Property', obj) );
	$detail.append( genTable('Data', 	 Site.getProperty('Data')) );
	// $detail.append( genTable('Raw Data', Site.getProperty('RawData')) );
}