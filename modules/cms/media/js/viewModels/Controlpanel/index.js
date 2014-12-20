define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/dashboard',
], function($, ko, site, dashboard){
	
	return function(){
		var self = this;
		
		self.dashboard = ko.observable();
		
		self.getCurrentDashboard = function(){
			$.getJSON(site.ajaxurl+'dashboards/current',function(reply){
				if(reply.success)
				{
					self.dashboard(new dashboard(reply));
				}
				else
				{
					site.tellUser(reply);
				}
			});
		};
		self.getCurrentDashboard();
		
		self.changeDashboard = function(undef,ev){
			if($(ev.target).val() != '')
			{
				$.post(site.ajaxurl+'dashboards/get',{id:$(ev.target).val()},function(reply){
					if(reply.success)
					{
						self.dashboard(new dashboard(reply));
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
		};
		
		self.addWidget = function(huhm, ev){
			var id = $(ev.target).prev('.widget-types').val();
			if(id > 0)
			{
				$.post(site.ajaxurl+'dashboards/addwidget',{
					widgettype:id,
					dashboard:self.dashboard().id
				},function(reply){
					if(reply.success)
					{
						self.dashboard().addWidget(reply);
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
		self.deleteDashboard = function(){
			if(confirm('Er du sikker på du vil slette panelet "'+self.dashboard().name+'"?'))
			{
				
			}
		};
		
	};
	
});
