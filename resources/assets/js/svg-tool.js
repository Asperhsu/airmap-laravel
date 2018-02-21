var svgTemplate = '\
	<svg width="{{width}}" height="{{height}}" viewBox="{{viewBox}}" xmlns="http://www.w3.org/2000/svg">\
		{{path}}\
		<text x="{{textOffsetX}}" y="{{textOffsetY}}"\
			fill="{{textColor}}" text-anchor="middle" \
			style="font-size:{{textSize}}px; font-weight: 800; ">\
			{{text}}\
		</text>\
	</svg>';

var defaultProperty = {
	width: 35,
	height: 35,
	viewBox: '0 0 40 40',
	path: '',
	text: {
		offset: {
			x: 15,
			y: 15,
		},
		color: "#FFFFFF",
		size: 35,
		value: '',
	},
	strokeColor: "#4F595D",
}

var getHtml = function(userProperty){
	var property = $.extend(true, {}, defaultProperty, userProperty);

	var svgHtml = svgTemplate.replace(/{{width}}/g, property.width)
						 .replace(/{{height}}/g, property.height)
						 .replace(/{{viewBox}}/g, property.viewBox)
						 .replace(/{{path}}/g, property.path)
						 .replace(/{{textOffsetX}}/g, property.text.offset.x)
						 .replace(/{{textOffsetY}}/g, property.text.offset.y)
						 .replace(/{{textColor}}/g, property.text.color)
						 .replace(/{{textSize}}/g, property.text.size)
						 .replace(/{{text}}/g, property.text.value);

	return svgHtml;
}

var toDataImage = function(svgHtml){
	return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgHtml);
}

var getContrastYIQ = function(hexcolor){
	//source: https://24ways.org/2010/calculating-color-contrast/
	hexcolor = hexcolor.replace('#', '');
	var r = parseInt(hexcolor.substr(0,2),16);
	var g = parseInt(hexcolor.substr(2,2),16);
	var b = parseInt(hexcolor.substr(4,2),16);
	var yiq = ((r*299)+(g*587)+(b*114))/1000;
	return (yiq >= 128) ? '#000000' : '#FFFFFF';
}

exports.getCircleUrl = function(color, text, size=40){
	var strokeWidth = 2;
	var strokeColor = defaultProperty.strokeColor;
	var fillColor = color;

	var path = '\
		<circle r="{{size}}" fill="{{fillColor}}" />\
	';
	path = path.replace(/{{size}}/g, size)
				.replace(/{{strokeWidth}}/g, strokeWidth)
				.replace(/{{strokeColor}}/g, strokeColor)
				.replace(/{{fillColor}}/g, fillColor);

	var property = {
		viewBox: "-55 -55 110 110",
		path: path,
		text: {
			offset: { x: 0, y: 13 },
			value: text,
			color: getContrastYIQ(color),
		}
	};

	var html = getHtml(property);
	return toDataImage(html);
}

exports.getHomeUrl = function(color, text){
	//copyright: <a href="http://www.freepik.com" title="Freepik">Freepik</a>
	var strokeWidth = 1;
	var strokeColor = defaultProperty.strokeColor;
	var fillColor = color;

	var path = '\
		<g fill="{{fillColor}}">\
			<path d="M33.609,20.96v12.384c0,1.104-0.896,2-2,2H7.805c-1.104,0-2-0.896-2-2V20.96c0-0.69,0.355-1.332,0.94-1.696l11.901-7.433\
				c0.648-0.405,1.472-0.405,2.119,0l11.901,7.433C33.253,19.628,33.609,20.269,33.609,20.96z M38.475,15.432L20.768,4.374\
				c-0.648-0.405-1.471-0.405-2.119,0L0.94,15.432c-0.937,0.585-1.221,1.819-0.637,2.756c0.584,0.938,1.816,1.224,2.756,0.638\
				L19.707,8.428l16.646,10.396c0.33,0.206,0.695,0.304,1.059,0.304c0.667,0,1.318-0.333,1.697-0.941\
				C39.695,17.249,39.41,16.017,38.475,15.432z"/>\
		</g>\
	';
	path = path.replace(/{{strokeWidth}}/g, strokeWidth)
				.replace(/{{strokeColor}}/g, strokeColor)
				.replace(/{{fillColor}}/g, fillColor);

	var property = {
		viewBox: "-2 -2 44 44",
		path: path,
		text: {
			offset: {
				x: 20,
				y: 32,
			},
			size: 14,
			value: text,
			color: getContrastYIQ(color),
		}
	};

	var html = getHtml(property);
	return toDataImage(html);
}

exports.getCloudUrl = function(color, text){
	//copyright: <a href="http://www.freepik.com" title="Freepik">Freepik</a>
	var strokeWidth = 10;
	var strokeColor = defaultProperty.strokeColor;
	var fillColor = color;

	var path = '\
		<g fill="{{fillColor}}">\
			<path d="M62.513,153.087c-0.009-0.525-0.02-1.049-0.02-1.575c0-50.155,40.659-90.814,90.814-90.814\
			c43.222,0,79.388,30.196,88.562,70.643c8.555-4.789,18.409-7.531,28.91-7.531c32.766,0,59.328,26.562,59.328,59.328\
			c0,1.339-0.06,2.664-0.148,3.981c24.325,9.03,41.661,32.444,41.661,59.911c0,35.286-28.605,63.892-63.892,63.892H79.865\
			C35.757,310.921,0,275.164,0,231.056C0,192.907,26.749,161.011,62.513,153.087z"/>\
		</g>\
	';
	path = path.replace(/{{strokeWidth}}/g, strokeWidth)
				.replace(/{{strokeColor}}/g, strokeColor)
				.replace(/{{fillColor}}/g, fillColor);

	var property = {
		viewBox: "-20 -20 420 420",
		path: path,
		text: {
			offset: {
				x: 180,
				y: 280,
			},
			size: 140,
			value: text,
			color: getContrastYIQ(color),
		}
	};

	var html = getHtml(property);
	return toDataImage(html);
}

exports.getFactoryUrl = function(color, text){
	//copyright: <a href="http://www.freepik.com" title="Freepik">Freepik</a>
	var strokeWidth = 13;
	var strokeColor = defaultProperty.strokeColor;
	var fillColor = color;

	var path = '\
		<path fill="{{fillColor}}" \
		d="M499.669,495.616C406.528,348.416,373.333,159.595,373.333,32c0-28.885-85.781-32-122.667-32C213.781,0,128,3.115,128,32\
		c0,104.875-15.04,304.555-115.669,463.616c-2.091,3.285-2.219,7.445-0.341,10.859c1.877,3.413,5.461,5.525,9.344,5.525h469.333\
		c3.883,0,7.467-2.112,9.344-5.525S501.76,498.923,499.669,495.616z M343.403,32.853c-0.747,0.235-1.429,0.469-2.24,0.683\
		c-2.091,0.597-4.459,1.195-7.061,1.771c-0.491,0.107-0.875,0.213-1.365,0.32c-3.2,0.683-6.784,1.365-10.688,2.005\
		c-1.003,0.171-2.176,0.32-3.221,0.469c-3.008,0.469-6.187,0.896-9.579,1.323c-1.6,0.192-3.285,0.363-4.971,0.555\
		c-3.221,0.341-6.592,0.661-10.112,0.96c-1.941,0.149-3.883,0.32-5.909,0.448c-3.797,0.256-7.829,0.469-11.947,0.661\
		c-1.963,0.085-3.84,0.192-5.867,0.256c-6.272,0.213-12.8,0.341-19.755,0.341c-6.955,0-13.483-0.128-19.755-0.341\
		c-2.027-0.064-3.904-0.171-5.867-0.256c-4.117-0.192-8.149-0.384-11.947-0.661c-2.027-0.149-3.989-0.299-5.909-0.448\
		c-3.52-0.299-6.891-0.597-10.112-0.96c-1.685-0.171-3.371-0.363-4.971-0.555c-3.392-0.405-6.571-0.853-9.579-1.323\
		c-1.045-0.171-2.219-0.32-3.221-0.469c-3.904-0.64-7.488-1.323-10.688-2.005c-0.512-0.107-0.875-0.213-1.365-0.32\
		c-2.603-0.576-4.992-1.173-7.061-1.771c-0.811-0.235-1.493-0.469-2.24-0.683c-0.981-0.299-1.813-0.597-2.645-0.896\
		c13.803-4.864,46.037-10.624,95.381-10.624s81.536,5.76,95.339,10.624C345.216,32.256,344.384,32.555,343.403,32.853z"/>\
	';
	path = path.replace(/{{strokeWidth}}/g, strokeWidth)
				.replace(/{{strokeColor}}/g, strokeColor)
				.replace(/{{fillColor}}/g, fillColor);

	var property = {
		viewBox: "-44 -44 600 600",
		path: path,
		text: {
			offset: {
				x: 250,
				y: 480,
			},
			size: 200,
			value: text,
			color: getContrastYIQ(color),
		}
	};

	var html = getHtml(property);
	return toDataImage(html);
}