define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	var menuitem = function(data){
		var self = this;
		
		self.id = data.id;
		self.content = data.content;
		self.linktext = ko.observable(data.linktext);
		self.titletext = ko.observable(data.titletext);
		self.classes = ko.observable(data.classes);
		self.order = ko.observable(data.order);
		self.parent = ko.observable(data.parent);
		self.children = ko.observableArray();
		self.type = data.type;
		self.url = ko.observable(data.url);
		
		self.open = ko.observable(false);
		self.toggleOpen = function(){
			self.open(!self.open());
		};
		
		self.removeme = function(){
			
			return false;
		};
		
		for(var i=0;i<data.children.length;i++)
		{
			self.children.push(new menuitem(data.children[i]));
		}
		
		self.getKids = function(elem, menu){
			var items = [];
			$(elem).find('> .widget-kids').find('> .widget').each(function(index, menuitem){
				var item = ko.dataFor(menuitem);
				items.push({
					id:item.id,
					content_id:item.content.id,
					menu_id:menu.id,
					url:item.url(),
					type:item.type,
					classes:item.classes(),
					linktext:item.linktext(),
					titletext:item.titletext(),
					kids:item.getKids(menuitem,menu),
					parent:self.id,
					order:index
				});
			});
			return items;
		};
		
		self.deleteMenuItem = function(item){
			if(confirm('Slet?'))
			{
				self.children.remove(item);
			}
		};
		
	};
	
	return menuitem;
	
});
