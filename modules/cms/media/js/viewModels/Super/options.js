define([
	'jquery',
	'knockout',
	'site',
	'models/Options/optiongroup'
], function($, ko, site, group){
	
	return function(data){
		var self = this;
		
		self.groups = ko.observableArray();
		
		for(var i=0;i<data.groups.length;i++)
		{
			self.groups.push(new group(data.groups[i]));
		}
		
	};
	
});
