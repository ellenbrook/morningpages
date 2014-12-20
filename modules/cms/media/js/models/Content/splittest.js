define([
	'knockout',
	'jquery',
	'site'
], function(ko, $, site){
	
	var splittest = function(data)
	{
		var self = this;
		
		self.id = data.id;
		self.title = data.title;
		self.hits = data.hits;
		self.parent_hits = data.parent_hits;
		self.created = data.created;
		
		self.status = ko.computed(function(){
			
		});
		
	};
	return splittest;
});
