define([
	'knockout',
	'jquery',
	'tinymce',
	'site',
	'models/Content/block',
	'models/Content/blocktype',
	'models/Tags/tag',
	'models/Files/file',
	'models/Content/splittest'
], function(ko, $, tinymce, site, block, blocktype, tag, file, splittest){
	
	return function(data){
		var self = this;
		
		self.id = self.id = $('#contentcontrols').data('id');
		self.contenttypeid = $('#contentcontrols').data('contenttypeid');
		self.blocks = ko.observableArray();
		self.thumbnail = ko.observable(false);
		self.tags = ko.observableArray();
		self.alltags = data.alltags;
		self.root = true;
		self.blocktypes = ko.observableArray();
		self.loading = ko.observable(true);
		self.splittests = ko.observableArray();
		
		self.availableBlocktypes = ko.observableArray();
		
		$.post(site.ajaxurl+'content/info',{id:self.id},function(reply){
			if(reply.success)
			{
				for(var i=0;i<reply.blocks.length;i++)
				{
					self.blocks.push(new block(reply.blocks[i]));
				}
				for(var i=0;i<reply.blocktypes.length;i++)
				{
					self.blocktypes.push(new blocktype(reply.blocktypes[i]));
				}
				for(var i=0;i<reply.tags.length;i++)
				{
					self.tags.push(new tag(reply.tags[i]));
				}
				for(var i=0;i<reply.splittests.length;i++)
				{
					self.splittests.push(reply.splittests[i]);
				}
				if(reply.thumbnail)
				{
					self.thumbnail(new file(reply.thumbnail));
				}
				self.updateAvailableBlocktypes();
				self.loading(false);
			}
			else
			{
				site.tellUser(reply);
			}
		}, 'json');
		
		self.updateAvailableBlocktypes = function(){
			self.availableBlocktypes.removeAll();
			ko.utils.arrayForEach(self.blocktypes(),function(blocktype){
				if(blocktype.max() == 0)
				{
					self.availableBlocktypes.push(blocktype);
				}
				var existing = [];
				ko.utils.arrayForEach(self.blocks(),function(block){
					if(blocktype.id == block.blocktype.id)
					{
						existing.push(block);
					}
				});
				if(existing.length<blocktype.max())
				{
					self.availableBlocktypes.push(blocktype);
				}
			});
		};
		
		self.createSplittest = function(unk, ev){
			$.post(site.ajaxurl+'splittest/create',{parent:self.id, title:$('#new-splittest-title').val()},function(reply){
				if(reply.success)
				{
					self.splittests.push(reply.splittest);
				}
				site.tellUser(reply);
			},'json');
		};
		
		self.copyContent = function(unk,ev){
			if(confirm('Are you sure you want to clone this content?'))
			{
				$(ev.target).attr('disabled','disabled');
				$.post(site.ajaxurl+'content/copy',{id:self.id},function(reply){
					if(reply.success)
					{
						window.location.hash = '/content/edit/'+reply.id;
					}
					else
					{
						$(ev.target).removeAttr('disabled');
					}
					site.tellUser(reply);
				}, 'json');
			}
		};
		
		self.removeBlock = function(block, ev)
		{
			if(confirm('Are you sure you want to delete this block?'))
			{
				$.post(site.ajaxurl+'blocks/delete',
				{
					'block_id' : block.id
				}, function(reply)
				{
					if (reply.success)
					{
						self.blocks.remove(block);
						self.updateAvailableBlocktypes();
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
			return false;
		};
		
		self.sortRootBlocks = function(e, iu){
			var i = 0;
			var blocks = {};
			$('#contentblocks').find('> .block').each(function(){
				var block = ko.dataFor(this);
				block.order = i;
				blocks[block.id] = i;
				i++;
			});
			$.post(site.ajaxurl + '/blocks/setorder', blocks);
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
			$.post(site.ajaxurl+'content/addtag',{id:self.id,tag:tagtext},function(reply){
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
		
		self.removeTag = function(elem){
			self.tags.remove(elem);
			$.post(site.ajaxurl+'content/removetag',{id:self.id,tag:elem.id},function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
					self.tags.push(new tag(elem));
				}
			},'json');
			return false;
		};
		
		self.selectthumbnail = function(files){
			if(files.length > 0)
			{
				var file = files[0];
				self.thumbnail(file);
			}
		};
		
		self.clearthumbnail = function(){
			self.thumbnail(false);
		};
		
		self.trash = function(){
			$.post(site.ajaxurl+'content/delete', {'delete':self.id}, function(reply){
				site.tellUser(reply);
				if(reply.success)
				{
					site.redirect('/content/index/'+self.contenttypeid);
				}
			}, 'json');
		};
		
		self.addBlock = function(context, elem){
			if ($(elem.target).prev('.blocktypes').val() != 0)
			{
				var $me = $(elem.target);
				var root = $me.parent().hasClass('complex');
				$.post(site.ajaxurl + '/blocks/add',
				{
					'blocktype_id' : $me.prev('.blocktypes').val(),
					'content_id' : self.id,
					'parent' : (context.root?0:context.id)
				}, function(reply) {
					if (reply.success)
					{
						context.blocks.push(new block(reply.block));
						self.updateAvailableBlocktypes();
						/*var $view = $(reply.block.view);
						$('#contentblocks').append($view).find('.empty').remove();
						site.scrollToElement($view);
						//var block = new block($view);
						var newblock = new block(reply.block, $view, self);
						self.blocks.push(newblock);
						ko.applyBindings(newblock, $view[0]);*/
						// Hide the option if it's at the max available number of blocks
						/*var $blocktypes = $me.prev('.blocktypes');
						var maxblocks = parseInt($blocktypes.find('option:selected').data('max'));
						if (maxblocks > 0)
						{
							var blocktypeid = $blocktypes.val();
							var existingblocks = $('.block[data-blocktypeid="' + blocktypeid + '"]').length;
							var newblocks = existingblocks + 1;
							if (newblocks >= maxblocks)
							{
								$blocktypes.find('option:selected').hide();
								$blocktypes.val(0);
							}
						}*/
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
			return false;
		};
		
		$('#content').off('click', '#content-delete-btn').on('click', '#content-delete-btn', function(){
			if(confirm('Sikker?'))
			{
				self.trash();
			}
			return false;
		});
	};
	
});
