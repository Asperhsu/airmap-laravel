var SVGTool = require("js/svg-tool");

module.exports = {
	formatter: {
		sn: function(value, row, index){
			return 1+index;
		},
		location: function(value, row, index){
			// var url = "https://www.google.com.tw/maps/place/" + value.lat + ',' + value.lng;
			var url = "/@" + value.lat + ',' + value.lng;
			var latlng = [value.lat, value.lng].join(', ');			

			return [
				`<a href="${url}" class="latlng" target="_blank" data-latlng="${latlng}">`,
				'<span class="glyphicon glyphicon-map-marker"></span>',
				'</a>'
			].join('');
		},
		detail: function(index, row, element){
			return [
				"<div class='col-sm-6'>",
				bsTable.generate.table("Data", ['Index', 'Value'], row.Data),
				"</div>",
				"<div class='col-sm-6'>",
				bsTable.generate.table("RawData", ['Index', 'Value'], row.RawData),
				"</div>",
			].join('');
		},
		updateTime: function(time){
			var human = moment(time).fromNow();
			var dataTime = moment(time).format('YYYY-MM-DD HH:mm');
			return '<span title="' + dataTime + '"><span class="glyphicon glyphicon-time"></span> ' + human + '</span>';
		},
		siteName: function(name, row){
			var url = "/site#" + row.SiteGroup + '$' + row.uniqueKey;
			return "<a href='" + url + "' target='_blank'><span class='glyphicon glyphicon-bookmark'></span> " + name + "</a>";
		},
		ranking: function(ranking){
			if(ranking == null){ return ''; }

			var html = [];
			var template = '<span class="glyphicon glyphicon-{{icon}}"></span>';
			for(var i=1; i<=5; i++){
				html.push(template.replace('{{icon}}', i<=ranking ? 'star' : 'star-empty'));
			}
			return html.join('');

			return value;
		},
		status: function(status){
			if(!status){ return ''; }

			var template = '<img src="{{url}}" title="{{hint}}" />';
			var hints = {
				'indoor': '可能放置於室內或設備故障',
				'longterm': '可能接近長時間的固定污染源或設備故障',
				'shortterm': '偵測到小型污染源',
			};
			var color = '#333';
			var text = '';
			var html = [];

			if(status.indexOf('indoor') > -1){ 
				html.push(template.replace('{{url}}', SVGTool.getHomeUrl(color, text)).replace('{{hint}}', hints['indoor'])); 
			}
			if(status.indexOf('longterm-pollution') > -1){ 
				html.push(template.replace('{{url}}', SVGTool.getFactoryUrl(color, text)).replace('{{hint}}', hints['longterm'])); 
			}
			if(status.indexOf('shortterm-pollution') > -1){ 
				html.push(template.replace('{{url}}', SVGTool.getCloudUrl(color, text)).replace('{{hint}}', hints['shortterm'])); 
			}
			
			return html.join('');
		},
		widget: function(name, row){
			var url = "/widget/create/" + row.SiteGroup + '$' + row.uniqueKey;
			return "<a href='" + url + "' target='_blank'><span class='glyphicon glyphicon-dashboard'></span></a>";
		},
	},
	generate: {
		table: function(title, head, body){
			var headHtml = '<tr><th>' + head.join('</th><th>') + '</th></tr>';
			
			var bodyHtml = '';
			for(var index in body){
				var value = body[index];
				// console.log(index, value);
				bodyHtml += [
					'<tr>',
						'<th>' + index + '</th>',
						'<td>' + value + '</td>',
					"</tr>"
				].join('');
			}

			return [
				'<div class="panel panel-info">',
					'<div class="panel-heading">',
						title,
					'</div>',
					"<table class='table table-striped'>",
						"<thead>",
							headHtml,
						"</thead>",
						"<tbody>",
							bodyHtml,
						"</tbody>",
					"</table>",
				'</div>',
			].join('');
		}
	},
}