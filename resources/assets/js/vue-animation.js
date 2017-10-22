var Vue = require("vue");

Vue.transition('slide', {
	css: false,
	enter: function (el, done) {
		$(el).hide().slideDown(done);
	},
	enterCancelled: function (el) {
		$(el).stop()
	},
	leave: function (el, done) {
		$(el).slideUp(done);
	},
	leaveCancelled: function (el) {
		$(el).stop()
	}
});