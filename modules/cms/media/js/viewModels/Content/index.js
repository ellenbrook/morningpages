define([
	'jquery',
	'knockout',
	'site',
	'models/Content/content',
	'models/Content/contenttype'
],function($, ko, site, content, contenttype){
	
	return function(data){
		var self = this;
		self.contents = ko.observableArray();
		self.contenttype = new contenttype(data.contenttype);
		self.allcontent = data.allcontent;
		
		for(var i=0;i<data.contents.length;i++)
		{
			self.contents.push(new content(data.contents[i]));
		}
		
		self.selectTypeaheadContent = function(val){
			site.redirect('/content/edit/'+val.id);
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
						self.contents.remove(content);
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
	};
	
});
