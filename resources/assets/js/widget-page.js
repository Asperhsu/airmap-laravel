var Indicator = require("js/measure-indicator");
moment.locale('zh-tw');

var site = $("body").data('site');
if(site){
	var pm25color = Indicator.getLevelColor(site.Data.Dust2_5);
	var createAt = (function(){
		var dd = moment.utc(site.Data.Create_at);
		var human = dd.toNow();
		var local = dd.local().format('YYYY-MM-DD HH:mm:ss');

		return {human, local};
	})();

	$(".pm25color").css('background-color', pm25color);
	$(".humanTime").text(createAt.human).attr('title', createAt.local);
}

var Widget = {};

//boot
var widgetType = $("body").data('widget-type');
if( Widget[widgetType] && Widget[widgetType].boot ){
	Widget[widgetType].boot();
}
