define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.key = data.key;
		self.type = data.type;
		self.title = data.title;
		self.description = data.description;
		self.editable = data.editable;
		
	};
	
});
