define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/widgettype'
], function($,ko,site,widgettype){
	
	return function(data){
		var self = this;
		
		self.widgettype = new widgettype(data.widgettype);
		self.id = data.id;
		self.loading = ko.observable(true);
		
		self.$elem = false;
		self.$content = false;
		self.load = function(elem){
			self.$elem = $(elem);
			self.$content = self.$elem.find('.widget-content-holder');
			// Fetch stuff
			self.$content.html('Alrighty.');
			self.loading(false);
			
		};
		
	};
	
});
