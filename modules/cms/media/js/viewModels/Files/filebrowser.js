define([
	'knockout',
	'jquery',
	'site',
	'models/Files/file',
	'models/Tags/tag',
	'jquery.fileupload'
], function(ko, $, site, file, tag){
	
	return function filebrowser()
	{
		var self = this;
		
		self.ready = ko.observable(false);
		self.modal = $('#filebrowsermodal');
		self.loading = ko.observable(false);
		self.files = ko.observableArray();
		self.filelimit = 50;
		self.fileoffset = 0;
		self.filesfetched = 0;
		self.fileselectlimit = 0;
		self.currentfile = ko.observable(false);
		self.selectedfiles = ko.observableArray();
		self.blockid = ko.observable(false);
		self.tags = ko.observableArray();
		self.activeTags = ko.observableArray();
		self.matchAllTags = ko.observable(false);
		self.limit = ko.observable(0);
		
		self.show = function(limit){
			if(!limit)
			{
				limit = 0;
			}
			self.limit(limit);
			self.modal.modal('show');
		};
		
		self.getSize = function(size){
			if(self.currentfile() && typeof self.currentfile().thumbnails != 'undefined')
			{
				if(typeof self.currentfile().thumbnails()[size] != 'undefined')
				{
					return self.currentfile().thumbnails()[size];
				}
			}
		};
		
		self.addTag = function(selected) {
			if(typeof selected == 'object')
			{
				tagtext = selected.title;
			}
			else
			{
				tagtext = selected;
			}
			$.post(site.ajaxurl+'files/addtag',{id:self.currentfile().id,tag:tagtext},function(reply){
				if(reply.success)
				{
					self.tags.push(new tag(reply.tag));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.selectFile = function(file)
		{
			if(self.currentfile())
			{
				self.currentfile().activefile(false);
			}
			if(file.selected())
			{
				// Remove
				file.selected(false);
				var files = self.selectedfiles;
				files.remove(file);
				var len = files().length;
				if(len > 0)
				{
					self.currentfile(files()[len-1]);
					self.currentfile().activefile(true);
				}
				else
				{
					self.currentfile(false);
				}
			}
			else
			{
				if(self.limit() == 0 || self.selectedfiles().length < self.limit())
				{
					// Add
					file.selected(true);
					self.currentfile(file);
					self.selectedfiles.push(file);
				}
				else if(self.limit() == 1)
				{
					self.selectedfiles.removeAll();
					if(self.currentfile())
					{
						self.currentfile().selected(false);
					}
					self.currentfile(file);
					file.selected(true);
					self.selectedfiles.push(file);
				}
				
			}
			return false;
		};
		
		self.insertFiles = function(){
			self.modal.trigger('files-selected', [self.selectedfiles()]);
			self.modal.modal('hide');
		};
		
		self.clearall = function(){
			ko.utils.arrayForEach(self.selectedfiles(), function(file){
				file.selected(false);
			});
			self.selectedfiles.removeAll();
			self.currentfile(false);
			return false;
		};
		
		self.selectAll = function(){
			self.selectedfiles.removeAll();
			self.currentfile(false);
			ko.utils.arrayForEach(self.files(), function(file){
				file.selected(true);
				self.selectedfiles.push(file);
			});
		};
		
		self.deleteCurrentFile = function(){
			if(confirm('Er du sikker på du vil slette denne fil permanent?'))
			{
				self.currentfile().selected(false);
				self.currentfile().permadelete();
				self.selectedfiles.remove(self.currentfile());
				self.files.remove(self.currentfile());
				self.tags.removeAll();
				self.getInfo();
				self.currentfile(false);
			}
			return false;
		};
		
		self.deleteSelectedFiles = function(){
			var files = self.selectedfiles();
			var selectedFilesLength = files.length;
			if(selectedFilesLength > 0)
			{
				var msg = 'Er du sikker på du vil slette de valgte filer permanent?';
				if(files.length == 1)
				{
					msg = 'Er du sikker på du vil slette den valgte fil permanent?';
				}
				if(confirm(msg))
				{
					self.loading(true);
					var ids = [];
					for(var i=0;i<files.length;i++)
					{
						ids.push(files[i].id);
					}
					$.post(site.ajaxurl+'filebrowser/delete', {id:ids}, function(reply){
						if(reply.success)
						{
							self.loading(false);
							for(var i=0;i<selectedFilesLength;i++)
							{
								self.files.remove(files[i]);
							}
							self.currentfile().selected(false);
							self.clearall();
							self.tags.removeAll();
							self.currentfile(false);
							self.selectedfiles.removeAll();
							self.getInfo();
						}
						else
						{
							site.tellUser(reply);
						}
					}, 'json');
				}
			}
			return false;
		};
		
		self.modal.on('show.bs.modal', function(){
			if(!self.ready() && !self.loading())
			{
				self.getInfo();
				self.getFiles();
				self.ready(true);
			}
		});
		
		self.getInfo = function(){
			$.getJSON(site.ajaxurl+'filebrowser/info', function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.tags.length;i++)
					{
						self.tags.push(new tag(reply.tags[i], self));
					}
				}
				else
				{
					site.tellUser(reply);
				}
			});
		};
		
		// Click to toggle matchAll
		self.toggleMatchAllTags = function(){
			if(self.activeTags().length > 0)
			{
				self.fileoffset = 0;
				self.clearall();
				self.files.removeAll();
				self.getFiles();
			}
		};
		
		// Click on tag
		self.toggleTag = function(elem, ev){
			self.fileoffset = 0;
			self.clearall();
			self.files.removeAll();
			if(elem.selected())
			{
				self.activeTags.remove(elem);
			}
			else
			{
				self.activeTags.push(elem);
			}
			elem.selected(!elem.selected());
			self.getFiles();
		};
		
		self.getActiveTags = function(){
			var tags = [];
			var loopto = self.activeTags().length;
			for(var i=0;i<loopto;i++)
			{
				tags.push(self.activeTags()[i].id);
			}
			return tags;
		};
		
		self.clearActiveTags = function(elem){
			var loopto = self.activeTags().length;
			for(var i=0;i<loopto;i++)
			{
				self.activeTags()[i].selected(false);
			}
			self.activeTags.removeAll();
			self.getFiles();
		};
		
		self.getFiles = function(){
			if(!self.loading())
			{
				self.loading(true);
				$.getJSON(site.ajaxurl+'filebrowser/files', {
					'limit':self.filelimit,
					'offset':self.fileoffset,
					'tags':self.getActiveTags(),
					'matchAll':self.matchAllTags()
				}, function(reply){
					self.loading(false);
					if(reply.success)
					{
						for(var i=0;i<reply.files.length;i++)
						{
							self.files.push(new file(reply.files[i], self));
						}
						self.fileoffset += reply.files.length;
						self.loading(false);
					}
					else
					{
						site.tellUser(reply);
					}
				});
			}
		};
		
		self.modal.on('hide.bs.modal', function(){
			//self.modal.off('files-selected');
			self.clearall();
		});
		
		self.saveFileTitle = function(me,ev){
			var newtitle = $(ev.target).val();
			if(self.currentfile() && self.currentfile().title() != newtitle)
			{
				$.post(site.ajaxurl+'files/save_file_title',{id:self.currentfile().id,title:newtitle},function(reply){
					// Do we care about the reply?
				},'json');
			}
		};
		
		self.saveFileAlt = function(me,ev){
			var newalt = $(ev.target).val();
			if(self.currentfile() && self.currentfile().alt() != newalt)
			{
				$.post(site.ajaxurl+'files/save_file_alt',{id:self.currentfile().id,alt:newalt},function(reply){
					// Do we care about the reply?
				},'json');
			}
		};
		
		self.saveFileDescription = function(me,ev){
			var newdesc = $(ev.target).val();
			if(self.currentfile() && self.currentfile().description() != newdesc)
			{
				$.post(site.ajaxurl+'files/save_file_description',{id:self.currentfile().id,description:newdesc},function(reply){
					// Do we care about the reply?
				},'json');
			}
		};
		
		self.currentThumbnail = ko.computed(function(){
			if(self.currentfile() && typeof self.currentfile().thumbnail == 'function')
			{
				return self.currentfile().thumbnail();
			}
			return '';
		});
		
		var remainingFiles = 0;
		$('#filebrowser-upload-btn').fileupload({
			url:site.ajaxurl+'/files/filebrowser_upload',
			add:function(e, data){
				remainingFiles++;
				data.uploadingfile = new file({});
				data.uploadingfile.progress(0);
				self.files.unshift(data.uploadingfile);
				data.submit();
			},
			done:function(e,data){
				remainingFiles--;
				reply = $.parseJSON(data.result);
				if(reply.success)
				{
					data.uploadingfile.progress(100);
					data.uploadingfile.id = reply.file.id;
					data.uploadingfile.thumbnail(reply.file.thumbnails.thumbnail);
					data.uploadingfile.mini(reply.file.thumbnails.mini);
					data.uploadingfile.medium(reply.file.thumbnails.medium);
					data.uploadingfile.filename(reply.file.filename);
					data.uploadingfile.image(reply.file.image);
					data.uploadingfile.path(reply.file.path);
					data.uploadingfile.description(reply.file.description);
					data.uploadingfile.filedate(reply.file.filedate);
					data.uploadingfile.filesize(reply.file.filesize);
					data.uploadingfile.filetype(reply.file.filetype);
					data.uploadingfile.title(reply.file.title);
					self.selectFile(data.uploadingfile);
					if(remainingFiles == 0)
					{
						//self.clearActiveTags();
						self.tags.removeAll();
						self.getInfo();
					}
				}
				else
				{
					self.files.remove(data.uploadingfile);
					site.tellUser(reply);
				}
			},
			progress:function(e,data){
				var progress = parseInt(data.loaded / data.total * 100, 10);
				data.uploadingfile.progress(progress);
			},
			error:function(){
				totalfilestoupload--;
			}
		});
		
	};
	
});
