var DataSource = {
	autoUpdateFlag: true,
	autoUpdateTS: null,
	autoUpdateIntervalms: 5 * 60 * 1000,
	boot: function(){
		this.loadSources();
		this.autoUpdate(true);
	},
	loadSources: function(){
		$("body").trigger("dataSourceLoadSources");

		// var bounds = MapHandler.getInstance().getBounds().toUrlValue();
		// var source = '/json/query-bounds?bounds=' + bounds;
		var source = '/json/airmap.json';

		this.fetch(source).then(results => {
			$("body").trigger("dataSourceLoadCompelete", [results]);
		});
	},
	fetch: function(source){
		return new Promise((resolve, reject) => {
			$.getJSON(source).done(function(data){
				resolve(data);
			});
		});
	},
	autoUpdate: function(flag){
		this.autoUpdateFlag = !!flag;

		if( this.autoUpdateFlag ){
			this.autoUpdateTS = setInterval(() => {
				this.loadSources();
			}, this.autoUpdateIntervalms)
		}else{
			clearInterval(this.autoUpdateTS);
		}
	},
	resetUpdate: function(){
		this.autoUpdate(false);
		this.autoUpdate(true);
	},
}

module.exports = DataSource;