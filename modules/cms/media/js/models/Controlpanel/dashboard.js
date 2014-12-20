define([
	'jquery',
	'knockout',
	'site',
	'models/Controlpanel/widgets/currentvisitors',
	'models/Controlpanel/widgets/errorpages',
	'models/Controlpanel/widgets/tester',
	'models/Controlpanel/widgets/todo',
	'models/Controlpanel/widgets/calendar',
	'models/Controlpanel/widgets/serverstats',
	'models/Controlpanel/widgets/feeds',
	'models/Controlpanel/widgets/popularcontent'
], function($,ko,site, currentvisitors, errorpages, tester, todo, calendar, serverstats, feeds, popularcontent){
	
	return function(data){
		var self = this;
		
		self.widgets = ko.observableArray();
		self.id = data.id;
		self.name = data.name;
		self.current = data.current;
		
		self.addWidget = function(data){
			switch(data.widgettype.type)
			{
				case 'currentvisitors':
					self.widgets.push(new currentvisitors(data));
					break;
				case 'errorpages':
					self.widgets.push(new errorpages(data));
					break;
				case 'tester':
					self.widgets.push(new tester(data));
					break;
				case 'todo':
					self.widgets.push(new todo(data));
					break;
				case 'calendar':
					self.widgets.push(new calendar(data));
					break;
				case 'serverstats':
					self.widgets.push(new serverstats(data));
					break;
				case 'sitestats':
					self.widgets.push(new serverstats(data));
					break;
				case 'feeds':
					self.widgets.push(new feeds(data));
					break;
				case 'popularcontent':
					self.widgets.push(new popularcontent(data));
					break;
			}
		};
		
		for(var i=0;i<data.widgets.length;i++)
		{
			self.addWidget(data.widgets[i]);
		}
		
		self.deleteWidget = function(widget){
			if(confirm('Er du sikke på du vil slette denne widget?'))
			{
				$.post(site.ajaxurl+'dashboards/deletewidget',{id:widget.id},function(reply){
					if(reply.success)
					{
						self.widgets.remove(widget);
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
		self.saveWidgetsOrder = function(){
			var order = {};
			$('#controlpanel-widgets').find('.block').each(function(index, block){
				order[ko.dataFor(block).id] = index;
				i++;
			});
			$.post(site.ajaxurl+'dashboards/savewidgetsorder',order,function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			},'json');
		};
		
	};
	
});
