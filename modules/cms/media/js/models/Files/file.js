define([
	'jquery',
	'knockout',
	'site',
	'models/Tags/tag',
	'models/Files/version'
], function($, ko, site, tag, version){
	
	var file = function(data)
	{
		var self = this;
		
		self.id = data.id;
		self.blockfileid = data.blockfileid;
		self.description = ko.observable(data.description);
		self.alt = ko.observable(data.alt);
		self.filedate = ko.observable(data.filedate);
		self.filename = ko.observable(data.filename);
		self.filesize = ko.observable(data.filesize);
		self.filetype = ko.observable(data.filetype);
		self.image = ko.observable(data.image);
		self.thumbnails = ko.observable(data.thumbnails);
		self.mini = ko.observable(data.thumbnails ? data.thumbnails.mini:'');
		self.medium = ko.observable(data.thumbnails ? data.thumbnails.medium:'');
		self.thumbnail = ko.observable(data.thumbnails ? data.thumbnails.thumbnail:'');
		self.path = ko.observable(data.path);
		self.tags = ko.observableArray();
		self.title = ko.observable(data.title);
		self.sizes = data.sizes;
		self.versions = ko.observableArray();
		
		if(data.file)
		{
			self.file = new file(data.file);
		}
		
		if(data.tags)
		{
			for(var i=0;i<data.tags.length;i++)
			{
				self.tags.push(new tag(data.tags[i]));
			}
		}
		
		if(data.versions)
		{
			for(var i=0;i<data.versions.length;i++)
			{
				self.versions.push(new version(data.versions[i]));
			}
		}
		
		self.displaysize = ko.observable('medium');
		self.selected = ko.observable(false);
		self.progress = ko.observable(100);
		self.activefile = ko.observable(false);
		
		self.uploadprogress = ko.computed(function(){
			return self.progress()+'%';
		});
		
		self.getSize = function(size){
			if(typeof self.thumbnails() != 'undefined')
			{
				if(typeof self.thumbnails()[size] != 'undefined')
				{
					return self.thumbnails()[size];
				}
			}
		};
		
		self.permadelete = function(){
			$.post(site.ajaxurl+'filebrowser/delete', {id:self.id}, function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.removeTag = function(elem){
			self.tags.remove(elem);
			$.post(site.ajaxurl+'files/removetag',{id:self.id,tag:elem.id},function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
					self.tags.push(new tag(elem));
				}
			},'json');
			return false;
		};
		
	};
	
	return file;
	
});
