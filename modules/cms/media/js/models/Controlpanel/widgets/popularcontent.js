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
		
		self.content = ko.observableArray();
		
		self.load = function(elem){
			self.$elem = $(elem);
			
			$.getJSON(site.ajaxurl+'widgets/popularcontent/get', function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.contents.length;i++)
					{
						self.content.push(reply.contents[i]);
					}
					self.loading(false);
				}
			});
			
		};
		
	};
	
});
