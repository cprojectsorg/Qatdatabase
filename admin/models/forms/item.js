jQuery(function() {
	document.formvalidator.setHandler('title',
		function (value) {
			regex=/[^A-Za-z0-9\-]/;
			return regex.test(value);
		});
});