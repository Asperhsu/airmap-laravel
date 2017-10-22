var SiteHistoryChart = {
	instance: null,
	elementID: "site-history-chart",
	lineColors: ['#F4A460', '#FF1493', '#20B2AA', '#ADFF2F', '#B0C4DE'],
	//https://developers.google.com/chart/interactive/docs/gallery/linechart
	options: {
		chartArea: { top:20, left:45, width: '90%', height: '80%' },
		legend: { position: 'bottom'},
		fontSize: 14,
		fontName: "Verdana",
		lineWidth: 2,
		pointSize: 4,
		hAxis: { gridlines: { color: "#fff" } },
		vAxis: { gridlines: { color: "#eee" } },
		explorer: {
			keepInBounds: true,
			maxZoomOut: 1,
		},
	},	
	start: function(data, options){
		google.charts.setOnLoadCallback(() => {
      		this.draw(data, options);
      	});

      	$(window).resize(() => {
			this.draw(data, options);
      	});
	},
	draw: function(data, options={}){
		if( !data.labels || !data.labels.length || !data.datasets || !data.datasets.length){
			var html = "<h4 style='text-align:center'><span class='glyphicon glyphicon-warning-sign'></span> No History data</h4>";
			$("#"+this.elementID).css('height', 'auto').html(html);

			return false;
		}
		var containerWidth = $("#"+this.elementID).width();
		var containerHeight =  containerWidth / 16 * 6;
		$("#"+this.elementID).css('height', containerHeight);

		if( !this.instance ){
			this.instance = new google.visualization.LineChart(document.getElementById(this.elementID));
		}

		var chartData = this.getData(data);
		var chartOptions = $.extend(true, this.options, options);

		this.instance.draw(chartData, chartOptions);
	},
	clear: function(){
		if( this.instance ){ this.instance.clearChart(); }
	},
	getData: function(data){
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn('datetime', 'Time');
		dataTable.addRows(data.labels.length);

		data.datasets.map( (line, index) => {
			dataTable.addColumn('number', line.label);
			for(var i in line.data){
				var value = line.data[i];
				if( isNaN(value) ){ value = 0; }
				
				dataTable.setCell(+i, 0, data.labels[i]);
				dataTable.setCell(+i, (index+1), value);
			}
		})
		return dataTable;
	},
	getRandColor: function(brightness){
		// source: http://stackoverflow.com/a/7352887
		//6 levels of brightness from 0 to 5, 0 being the darkest
		var rgb = [Math.random() * 256, Math.random() * 256, Math.random() * 256];
		var mix = [brightness*51, brightness*51, brightness*51]; //51 => 255/5
		var mixedrgb = [rgb[0] + mix[0], rgb[1] + mix[1], rgb[2] + mix[2]].map(function(x){ return Math.round(x/2.0)})
		return "rgb(" + mixedrgb.join(",") + ")";
	}
};

module.exports = SiteHistoryChart;