var Site = require("js/site-model");

var showSitesInMap = true;
var sites = [];
var groupCount = {};
var analysisCount = {};

var activeGroups = null;
var activeStatus = null;

function addSite(Site){
	sites.push(Site);
}

function clearSites(){
	for(var i in sites){
		var site = sites[i];
		var marker = site.getMarker();
		if(marker){ marker.setMap(null); }
		delete sites[i];
	}
	sites = [];
}
exports.clear = clearSites;

function setGroupCount(Site){
	if(!Site){ return false; }

	var siteGroup = Site.getProperty('SiteGroup');
	if( !siteGroup.length ){ return false; }

	if(!groupCount[siteGroup]){ groupCount[siteGroup] = 0;	}
	groupCount[siteGroup]++;
}
function getGroups(){
	if( Object.keys(groupCount).length ){
		return groupCount;
	}

	for(var i in sites){
		var site = sites[i];
		setGroupCount(site);
	}
	return groupCount;
}
exports.getGroups = getGroups;

function setAnalysisCount(Site){
	if(!Site){ return false; }

	var status = Site.getProperty('Analysis.status');
	if (!status) { return false; }
	
	if(status === null){ status = 'normal'; }
	if(status.indexOf('indoor') > -1){ status = 'indoor'; }
	if(status.indexOf('shortterm') > -1){ status = 'shortterm'; }
	if(status.indexOf('longterm') > -1){ status = 'longterm'; }


	if(!analysisCount[status]){ analysisCount[status] = 0;	}
	analysisCount[status]++;
}
function getAnalysisCount(){
	if( Object.keys(analysisCount).length ){
		return analysisCount;
	}

	for(var i in sites){
		var site = sites[i];
		setAnalysisCount(site);
	}
	return analysisCount;
}
exports.getGroups = getGroups;

function countSitesInView(){
	var sitesCountInView = 0;
	var Bounds = MapHandler.getInstance().getBounds();
	for(var i in sites){
		var site = sites[i];
		if( Bounds && Bounds.contains(site.getPosition()) && site.getMarker() && site.getMarker().getMap() ){
			sitesCountInView++;
		}
	}
	$("#info-on-map").text(sitesCountInView);
}

function bindEvents(){
	MapHandler.addListener('bounds_changed', function(){
		countSitesInView();
	});

	$("body")
		.on("site_changeCategory", function(e, actives){
			countSitesInView();
			changeGroups(actives);
		})
		.on("toggleLayer", function(e, type, state){
			if( type != 'siteLayer'){ return; }
			toggleSitesInMap(state);
		})
		.on("filterStatus", function(e, actives){
			countSitesInView();
			filterAnalysisStatus(actives);
		})
		.on("indicatorTypeChange", function(e, type){
			updateMarkers();
		});
}

function boot(){
	bindEvents();
}
exports.boot = boot;

function loadSites(data){
	if(!data || !data.length){ return false; }
	clearSites();

	for(var i in data){
		var site = new Site(data[i]);
		if( !site.isValid() ){ continue; }

		site.createMarker({onMap: false});
		setGroupCount(site);
		setAnalysisCount(site);
		addSite(site);
	}
	
	$("body").trigger("sitesLoaded", [getGroups(), getAnalysisCount()]);
	countSitesInView();
}
exports.loadSites = loadSites;

function toggleSitesInMap(show){
	if( typeof show == "undefined" ){
		showSitesInMap = !showSitesInMap;
	}else{
		showSitesInMap = !!show;
	}			

	for(var i in sites){
		var site = sites[i];
		site.toggleMarker(showSitesInMap);
	}
	return showSitesInMap;
}
exports.toggleLayer = toggleSitesInMap;

function isInVisibleGroup(actives, Site){
	var group = Site.getProperty('SiteGroup');
	return actives.indexOf(group) > -1;
}

function isInVisibleStatus(actives, Site){
	var status = Site.getProperty('Analysis.status');
	
	status = status === null ? ['normal'] : status.split('|');

	var isShow = false;
	status.map(function(stat){
		isShow = isShow || actives.indexOf(stat) > -1;
	});
	return isShow;
}

function filterActiveSites(){
	for(var i in sites){
		var Site = sites[i];

		var inGroup = activeGroups === null ? true : isInVisibleGroup(activeGroups, Site);
		var inStatus = activeStatus === null ? true :  isInVisibleStatus(activeStatus, Site);

		Site.toggleMarker(inGroup && inStatus);
	}
}

function changeGroups(actives){
	if(!actives){ return false; }

	activeGroups = actives;
	filterActiveSites();
}
exports.changeGroups = changeGroups;

function filterAnalysisStatus(actives){
	if(!actives){ return false; }

	activeStatus = actives;
	filterActiveSites();
}
exports.filterStatus = filterAnalysisStatus;

function updateMarkers(){
	for(var i in sites){
		var Site = sites[i];
		Site.updateMarkerColor();
	}
}
exports.updateMarkers = updateMarkers;

function getVoronoiData(){
	var locations = [];
	var colors = [];
	for(var i in sites){
		var site = sites[i];

		var LatLng = site.getPosition();
		locations[i] = [LatLng.lat(), LatLng.lng()];
		colors[i] = site.getMeasureColor();
	}
	return {
		locations: locations,
		colors: colors,
	}
}
exports.getVoronoiData = getVoronoiData;

function search(string){
	if( !string || !string.length ){ return {}; }

	var results = [];
	sites.map(Site => {
		var searched = Site.match(string);
		if(searched){
			searched.map(value => {
				results.push({
					name: value,
					instance: Site,
				});
			});
		}
	});
	
	return results;
}
exports.search = search;