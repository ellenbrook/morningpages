define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/dashboard',
], function($, ko, site, dashboard){
	
	return function(){
		var self = this;
		
		self.dashboard = ko.observable({id:0,widgets:[]});
		self.dashboards = ko.observableArray();
		self.loaded = false;
		
		
		self.getDashboards = function(){
			$.getJSON(site.ajaxurl+'dashboards/getall',function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.dashboards.length;i++)
					{
						var newdb = new dashboard(reply.dashboards[i]);
						self.dashboards.push(newdb);
						if(newdb.current == "1")
						{
							self.dashboard(newdb);
						}
					}
				}
				else
				{
					site.tellUser(reply);
				}
				self.loaded = true;
			});
		};
		self.getDashboards();
		
		self.changeDashboardEvent = function(undef,ev){
			if($(ev.target).val() != null && $(ev.target).val() != self.dashboard().id)
			{
				self.changeDashboard($(ev.target).val());
			}
		};
		
		self.loaded = false;
		self.changeDashboard = function(id) {
			if(self.loaded)
			{
				$.post(site.ajaxurl+'dashboards/get',{id:id},function(reply){
					if(reply.success)
					{
						self.dashboard(new dashboard(reply.dashboard));
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
		};
		
		self.deleteDashboard = function(one, two){
			if(confirm('Sure you want to delete the panel named "'+self.dashboard().name+'"?'))
			{
				$.post(site.ajaxurl+'dashboards/delete', {id:self.dashboard().id},function(reply){
					if(reply.success)
					{
						var dashboards = self.dashboards();
						for(i=0;i<dashboards.length;i++)
						{
							if(dashboards[i].id == self.dashboard().id)
							{
								self.dashboards.remove(dashboards[i]);
								if(self.dashboards().length == 0)
								{
									self.dashboards.push(new dashboard(reply.dashboard));
								}
								else
								{
									self.dashboard(self.dashboards()[0]);
								}
								break;
							}
						}
						//self.dashboards.remove(function(item){if(item.id === self.dashboard().id){console.log(item);return item;};});
						
					}
					site.tellUser(reply);
				},'json');
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
		
		self.createDashboard = function(){
			var name = prompt('Name of your dashboard');
			if(!name || name.length == 0)
			{
				return;
			}
			else
			{
				$.post(site.ajaxurl+'dashboards/create', {name:name},function(reply){
					if(reply.success)
					{
						var newdb = new dashboard(reply);
						self.dashboards.push(newdb);
						self.dashboard(newdb);
					}
					site.tellUser(reply);
				}, 'json');
			}
		};
		
	};
	
});
