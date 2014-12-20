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
		
		self.errors = ko.observableArray();
		
		self.load = function(elem){
			self.$elem = $(elem);
			
			$.getJSON(site.ajaxurl+'widgets/errorpages/get', function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.errors.length;i++)
					{
						self.errors.push(reply.errors[i]);
					}
					self.loading(false);
				}
			});
		};
		
	};
	
});
