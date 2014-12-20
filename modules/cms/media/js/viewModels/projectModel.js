define([
	'knockout',
	'viewModels/Files/filebrowser'
], function(ko, filebrowser){
	
	return function(){
		var self = this;
		
		//self.filebrowser = new filebrowser();
		self.page = ko.observable('hi');
		//self.contentModel = ko.observable(new contentModel());
	};
	
});
