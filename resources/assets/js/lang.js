var Cookies = require("js.cookie");

var LANG = {
	translation: {
		"en-US": {
			"pageTitle": "g0v Realtime Air Pollution Map",
			"recruit": "MicroStation Maintainer Recruit",

			"group": 					"Group",
			"display": 					"Display",
			"opacity": 					"Opacity",

			"selectAll": 				"Select All",
			"selectNone": 				"DeSelect All",

			"siteFilter": 				"Site Filter",
			"siteList": 				"Sites List",
			"mapTool": 					"Map Tool",
			"siteChart": 				"Site Chart",
			"siteComment": 				"Site Comment",
			"measureType": 				"Measure Type",
			"districtChange": 			"District Change",
			"voronoiDiagram": 			"Voronoi Diagram",
			"windLayer": 				"Wind Layer",
			"windResourceAlert": 		"Wind layer consume lots resources, easy to cause browser crash. at your discretion.",
			"lastUpdate": 				"Last update",
			"halfHourUpdate": 			"update at half clock",

			"windFillOpacity": 			"Wind opacity",
			"windMoveSpeed": 			"Wing flow speed",

			"resourceLayer": 			"Resource Layer",
			"emissionLayer": 			"Emission",
			"displayEmissionStaton": 	"Show Emission Station",
			
			"cwbImage": 				"CWB Cloud Image",
			"imageProjectionNotEqual": 	"Satellite image using different projection with map, position not equal.",

			"selectSiteFirst": 			"Please Select Site on Map",
			"lastHourChart": 			"Last hour chart",
			"lastDayChart": 			"Last day chart",
			"lastWeekChart": 			"Last week chart",
			"lastMonthChart": 			"Last month chart",

			"externalLink": 			"External Link",

			"ranking": 					"Data Reliability",
			"historyChart": 			"History Chart",
			"independentPage": 			"Independent Page",

			"visibleSiteCount": 		"Visible site count",
			"disclaimer": 				"This map provide visualize from public data, do not guarantee data accuracy.",
		},
		"zh-TW":{
			"pageTitle": 				"g0v零時空汙觀測網",
			"recruit": 					"自造站點募集中",

			"group": 					"群組",
			"display": 					"顯示",
			"opacity": 					"透明度",

			"selectAll": 				"全選",
			"selectNone": 				"全不選",

			"siteFilter": 				"測站篩選",
			"siteList": 				"站點清單",
			"mapTool": 					"地圖工具",
			"siteChart": 				"測站圖表",
			"siteComment": 				"測站討論",
			"measureType": 				"量測類別",
			"districtChange": 			"區域切換",
			"voronoiDiagram": 			"勢力地圖",
			"windLayer": 				"風力線",
			"windResourceAlert": 		"風力線十分消耗資源，容易造成瀏覽器當機，請斟酌使用。",
			"lastUpdate": 				"資料時間",
			"halfHourUpdate": 			"半整點更新資料",

			"windFillOpacity":			"線條亮度",
			"windMoveSpeed": 			"移動速度",

			"resourceLayer": 			"資源圖層",
			"emissionLayer": 			"固定汙染源",
			"displayEmissionStaton": 	"顯示站點",

			"cwbImage": 				"氣象雲圖",
			"imageProjectionNotEqual": 	"雲圖與地圖投影法不相同，位置會有誤差。",

			"selectSiteFirst": 			"請先選擇站點",
			"lastHourChart": 			"過去一小時歷史數值",
			"lastDayChart": 			"過去一天歷史數值",
			"lastWeekChart": 			"過去一週歷史數值",
			"lastMonthChart": 			"過去一個月歷史數值",

			"externalLink": 			"資源連結",

			"ranking": 					"資料可信度",
			"historyChart": 			"歷史圖表",
			"independentPage": 			"站點詳細頁面",

			"visibleSiteCount": 		"可見站點數量",
			"disclaimer": 				"本零時空汙觀測網僅彙整公開資料提供視覺化參考，並不對資料數據提供保證，實際測值以各資料來源為準。",
		}
	},
	currentLang: null,
	boot: function(){
		var userLang = Cookies.get('language') || navigator.language || navigator.userLanguage;

		if( Object.keys(this.translation).indexOf(userLang) > -1 ){
			this.currentLang = userLang;
		}else{
			this.currentLang = "zh-TW";
		}

		this.translateApp();

		$("body").on("languageChange", function(e, lang){
			this.setLang(lang);
			translate();
		}.bind(this));
	},
	get: function(index){
		if( this.translation[this.currentLang] && this.translation[this.currentLang][index] ){
			return this.translation[this.currentLang][index];
		}
		return index + ' not found'
	},
	translateElement: function($el, index){
		if( !$el || !index ){ return false; }

		var text = this.get(index);

		if( $el.is("input:button") ){
			$el.val(text);
			return;
		}

		if( $el[0].hasAttribute("title") ){
			$el.attr('title', text);
			return;
		}

		if( !$el.children().length ){
			$el.text(text);
			return;
		}
	},
	translateApp: function($container){
		var $target = $("[data-lang]");
		if( $container ){
			$target = $container.find("[data-lang]");
		}

		$target.each(function(){
			LANG.translateElement($(this), $(this).data('lang'));
		});
		return this;
	},
	setLang: function(lang){
		this.currentLang = lang;
		moment.locale(lang);
		Cookies.set('language', lang);
		return this;
	},
	getLang: function(){
		return this.currentLang;
	}
};

module.exports = LANG;