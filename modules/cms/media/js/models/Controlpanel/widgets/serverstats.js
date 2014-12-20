define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/widgettype'
], function($,ko,site,widgettype){
	
	/**
	 * Network, CPU load, load rate (?) osv.
	 * Info om RAM ol?
	 */
	
	return function(data){
		var self = this;
		
		self.widgettype = new widgettype(data.widgettype);
		self.id = data.id;
		self.loading = ko.observable(true);
		
		self.$elem = false;
		
		self.load = function(elem){
			self.$elem = $(elem);
			
			/*$.get(site.ajaxurl+'widgets/serverstats/get',function(reply){
				console.log(reply);
			});*/
			
		};
		
	};
	
});
