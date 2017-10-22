// require("expose?bsTable!./list-table");
window.bsTable = require("./list-table");
require("css/common.css");
require("css/list.css");


moment.locale('zh-tw');
moment.updateLocale('zh-tw', {
	relativeTime: {
		m : '1分鐘',
		h : '1小時',
		d : '1天',
		M : '1個月',
		y : '1年',
	}
});



$(function(){
	// siteName hashtag
	var siteName = window.location.hash.substr(1);	
	$(".bsTable").on('load-success.bs.table', function(e, data){
		if(siteName){
			$(".bootstrap-table .search input").val(siteName).trigger('keyup');
		}
	});

	$(".bsTable").on('click-row.bs.table', function(e, row, $tr){
		if ($tr.next().is('tr.detail-view')) {
			$(this).bootstrapTable('collapseRow', $tr.data('index'));
		} else {
			$(this).bootstrapTable('expandRow', $tr.data('index'));
		}
	});

	$("#filter button").click(function(){
		if($(this).data('type')){
			$("#filter button[data-type]").removeClass('btn-primary')
				.filter(this).addClass('btn-primary');
		}

		if($(this).data('group')){
			$("#filter button[data-group]").removeClass('btn-success')
				.filter(this).addClass('btn-success');
		}

		var group = $("#filter button.btn-success[data-group]").data('group');
		loadDatasource(group);
	});

	function loadDatasource(filename){
		if(!filename){ return false; }

		$("#loading").show();

		var url = "/json/{{filename}}.json";
		url = url.replace('{{filename}}', filename);

		$.getJSON(url).then(data => {
			$('#bsTable').bootstrapTable('load', data);
			$("#loading").hide();
		});
	}

	google.maps.event.addDomListener(window, "load", function(){
		var geocoder = new google.maps.Geocoder();
		var getAddr = function(lat, lng, cb){
			var coord = new google.maps.LatLng(lat, lng);
			geocoder.geocode({'latLng': coord }, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK && results) {	
					var address = results[0].formatted_address;

					var components = results[0].address_components;
					if( components.length > 4 ){						
						components.shift(); components.pop();
						var seperate = components[components.length-1].short_name == "TW" ? '' : ', ';
						address = components.reverse().map((component) => component.long_name).join(seperate);						
					}

					cb(address);
					return;
				}
				cb('');
			});
		}

		$("body").on("mouseover", "a.latlng", function(){
			var $el = $(this);
			var [lat, lng] = $(this).data('latlng').split(',');
			getAddr(lat, lng, function(addr){
				$el.attr('title', addr + ` (${$el.data('latlng')})` );
			});			
		});
	});
});