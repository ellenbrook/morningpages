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
		
		self.username = 'Unknown';
		self.client = '';
		self.ip = '';
		self.referrer = '';
		self.time = '';
		self.visitors = ko.observableArray();
		
		self.$elem = false;
		
		self.load = function(elem){
			self.$elem = $(elem);
			
			$.getJSON(site.ajaxurl+'widgets/currentvisitors/get', function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.visitors.length;i++)
					{
						self.visitors.push(reply.visitors[i]);
					}
					self.loading(false);
				}
			});
			
		};
		
	};
	
});
