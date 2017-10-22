var webpack = require("webpack");
var path = require("path");
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var outputPath = "public/";
var resourcePath = __dirname + "/assets/";

module.exports = {
	devtool: "source-map",
	entry: {
		map: [
			resourcePath + "js/map-page",
			resourcePath + "js/navigator-handler",
		],
		list: resourcePath + "js/list-page",
		site: resourcePath + "js/site-page",
		widget: resourcePath + "js/widget-page",
		"vendor-common": ["jquery",  "moment"],
		"vendor-map": [
			"vue", "bootstrap-switch", "bootstrap-slider", 
			"js.cookie", "d3"
		],
	},
	output: {
		path: outputPath + "js",
    	publicPath: "js/",
    	filename: '[name].js',
	},
	module: {
		loaders: [
			{ test: /\.js$/, loader: 'babel-loader?presets[]=es2015', exclude: /(node_modules|bower_components)/ },
			{ test: /\.json$/, loader: "json-loader" },
			{ test: /\.css$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader') },
		],
	},
	externals: {
        "jquery": "jQuery"
    },
	resolve: {
		root: [
			path.resolve(resourcePath)
		],
	},
	plugins: [
	    new ExtractTextPlugin('../css/[name].css'),
		new webpack.ContextReplacementPlugin(/moment[\/\\]locale$/, /zh-tw/),
	    new webpack.ProvidePlugin({
	        $: 'jquery',
	        jQuery: 'jquery',
	        'window.jQuery': 'jquery',
	        'root.jQuery': 'jquery',
	        'moment': 'moment',
	        'MapHandler': 'js/map-handler',
	    }),
	]
};