define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	return function(file) {
		var self = this;
		
		self.filename = file.filename();
		self.path = file.path();
		
		console.log(file);
		console.log(file.filename());
		return false;
	};
	
});
