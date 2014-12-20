define([
	'jquery',
	'knockout',
	'site',
	'models/Options/option'
], function($, ko, site, option){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.display = data.display;
		self.options = ko.observableArray();
		self.isopen = ko.observable(false);
		
		for(var i=0;i<data.options.length;i++)
		{
			self.options.push(new option(data.options[i]));
		}
		
		self.deleteoption = function(item, event){
			if(confirm('Delete this option?'))
			{
				$.post(site.ajaxurl+'options/delete',{id:item.id},function(reply){
					if(reply.success)
					{
						self.options.remove(item);
					}
				},'json');
			}
		};
		
		self.newoptiontitle = ko.observable();
		self.newoptiontype = ko.observable('text');
		self.newoptioneditable = ko.observable(1);
		self.newoptionkey = ko.observable();
		self.newoptionsdescription = ko.observable();
		
		self.addoption = function(){
			if(self.newoptiontitle().length > 0)
			{
				$.post(site.ajaxurl+'options/add',{
						group:self.id,
						title:self.newoptiontitle(),
						key:self.newoptionkey(),
						type:self.newoptiontype(),
						description:self.newoptionsdescription,
						editable:self.newoptioneditable()
				},function(reply){
					if(reply.success)
					{
						self.options.push(new option(reply.option));
					}
					site.tellUser(reply);
				},'json');
			}
		};
		
	};
	
});
