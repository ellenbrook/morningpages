define([
	'jquery',
	'knockout',
	'site',
	'models/Navigation/menuitem'
], function($, ko, site, menuitem){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.title = ko.observable(data.title);
		self.items = ko.observableArray();
		self.menutype_id = ko.observable();
		if(data.menutype_id != 0)
		{
			self.menutype_id(data.menutype_id);
		}
		
		for(var i=0;i<data.menuitems.length;i++)
		{
			self.items.push(new menuitem(data.menuitems[i]));
		}
		
		self.addInternalItem = function(data){
			self.items.push(new menuitem(data));
		};
		
	};
	
});
