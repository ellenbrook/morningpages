define([
	'jquery',
	'knockout',
	'site',
	'models/Files/file'
], function($, ko, site, file){
	
	return function(data){
		var self = this;
		
		self.files = ko.observableArray();
		
		for(var i=0;i<data.files.length;i++)
		{
			self.files.push(new file(data.files[i]));
		}
		
	};
	
});
