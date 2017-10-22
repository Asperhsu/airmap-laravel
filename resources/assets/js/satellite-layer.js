function SatelliteLayer(id, bounds, image){
	this.divID = id;
	this.div = null;
	this.bounds = bounds;
	this.image = image;
	this.map = MapHandler.getInstance();
	this.setMap(this.map);
};

SatelliteLayer.prototype = MapHandler.createOverlayView();

SatelliteLayer.prototype.onAdd = function() {
	var div = document.createElement('div');
	div.id = this.divID;
	div.style.borderStyle = 'none';
	div.style.borderWidth = '0px';
	div.style.position = 'absolute';

	// Create the img element and attach it to the div.
	var img = document.createElement('img');
	img.src = this.image;
	img.style.width = '100%';
	img.style.height = '100%';
	img.style.position = 'absolute';
	div.appendChild(img);

	this.div = div;

	// Add the element to the "overlayLayer" pane.
	var panes = this.getPanes();
	panes.overlayLayer.appendChild(div);

};
SatelliteLayer.prototype.draw = function() {
	var overlayProjection = this.getProjection();
	var sw = overlayProjection.fromLatLngToDivPixel(this.bounds.getSouthWest());
	var ne = overlayProjection.fromLatLngToDivPixel(this.bounds.getNorthEast());

	// Resize the image's div to fit the indicated dimensions.
	var div = this.div;
	div.style.left = sw.x + 'px';
	div.style.top = ne.y + 'px';
	div.style.width = (ne.x - sw.x) + 'px';
	div.style.height = (sw.y - ne.y) + 'px';
};
SatelliteLayer.prototype.onRemove = function() {
	this.div.parentNode.removeChild(this.div);
	this.div = null;
};
SatelliteLayer.prototype.toggle = function(flag) {
	if( !this.div ){ return false; }

	if( typeof flag == "undefined" ){
		flag = this.div.style.visibility === 'hidden' ? true : false;	//reverse
	}else{
		flag = !!flag;
	}

	this.div.style.visibility = flag ? 'visible' : 'hidden';
};


module.exports = SatelliteLayer;