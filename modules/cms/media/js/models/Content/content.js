define([
	'jquery',
	'knockout',
	'site',
	'models/Content/contenttype'
], function($,ko,site, contenttype){
	
	var content = function(data){
		var self = this;
		
		self.isopen = ko.observable(false);
		self.id = data.id;
		self.title = ko.observable(data.title);
		self.tmptitle = ko.observable(self.title());
		self.children = ko.observableArray();
		self.status = ko.observable(data.status);
		self.tmpstatus = ko.observable(self.status());
		self.statustext = ko.observable(data.statustext);
		self.contenttype = new contenttype(data.contenttype);
		self.url = ko.observable(data.url);
		
		self.contenttypetype = data.contenttypetype;
		
		for(var i=0;i<data.children.length;i++)
		{
			self.children.push(new content(data.children[i]));
		}
		
		self.statusClass = ko.computed(function(){
			switch(self.status())
			{
				case 'draft':
				default:
					return 'label-default';
					break;
				case 'active':
					return 'label-success';
					break;
			}
		});
		
		self.save = function(){
			$.post(site.ajaxurl+'content/quicksave',{
				id:self.id,
				status:self.tmpstatus(),
				title:self.tmptitle()
			}, function(reply){
				if(reply.success)
				{
					self.status(reply.info.status);
					self.statustext(reply.info.statustext);
					self.title(reply.info.title);
					self.tmptitle(self.title());
				}
				site.tellUser(reply);
			},'json');
		};
		
		self.deleteContent = function(content){
			var msg = 'Er du sikker på du vil slette dette indhold';
			if(content.children().length>0)
			{
				msg += ' samt alt underindhold?';
			}
			if(confirm(msg))
			{
				$.post(site.ajaxurl+'content/delete',{delete:content.id},function(reply){
					if(reply.success)
					{
						self.children.remove(content);
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
	};
	
	return content;
	
});
