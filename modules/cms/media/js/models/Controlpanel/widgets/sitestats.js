define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/widgettype'
], function($,ko,site,widgettype){
	
	/**
	 * Besøgende, bounce rates, browser stats osv.
	 * http://themes-lab.com/preview/?theme=Pixit%20Admin
	 */
	
	return function(data){
		var self = this;
		
		self.widgettype = new widgettype(data.widgettype);
		self.id = data.id;
		self.loading = ko.observable(true);
		
		self.$elem = false;
		
		self.load = function(elem){
			self.$elem = $(elem);
		};
		
	};
	
});
