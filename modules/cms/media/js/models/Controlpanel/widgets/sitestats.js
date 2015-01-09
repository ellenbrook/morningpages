define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/widgettype'
], function($,ko,site,widgettype){
	
	/**
	 * Visitors, bounce rates, browser stats etc?
	 * http://themes-lab.com/preview/?theme=Pixit%20Admin
	 */
	
	return function(data){
		var self = this;
		
		self.widgettype = new widgettype(data.widgettype);
		self.id = data.id;
		self.loading = ko.observable(true);
		self.newusers = ko.observable(0);
		self.pages = ko.observable(0);
		
		self.$elem = false;
		
		self.load = function(elem){
			self.$elem = $(elem);
			
			$.getJSON(site.ajaxurl+'widgets/sitestats/get', function(reply){
				if(reply.success)
				{
					self.newusers(reply.newusers);
					self.pages(reply.pages);
					self.loading(false);
				}
			});
			
		};
		
	};
	
});
