define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.tag = data.tag;
		self.slug = data.slug;
		
		self.selected = ko.observable(false);
		
	};
	
});
