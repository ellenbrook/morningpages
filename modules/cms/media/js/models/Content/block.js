define([
	'knockout',
	'jquery',
	'site',
	'models/Files/file',
	'models/Content/blocktype'
], function(ko, $, site, file, blocktype){
	
	var block = function(data)
	{
		var self = this;
		
		self.id = data.id;
		self.order = data.order;
		self.parent = data.parent;
		self.contentid = data.contentid;
		self.blocktype = new blocktype(data.blocktype);
		self.value = ko.observable(data.value);
		self.excerpt = ko.observable(data.excerpt);
		self.collapsed = ko.observable(data.collapsed);
		self.filelimit = ko.observable(0);
		self.childCount = data.childCount;
		self.loading = ko.observable(true);
		
		self.blocks = ko.observableArray();
		self.files = ko.observableArray();
		
		self.formelementname = ko.computed(function(){
			return 'block['+self.id+']';
		});
		
		self.fileformelementname = function(id, last){
			return 'block['+self.id+'][files]['+id+']['+last+']';
		};
		
		self.availableBlocktypes = ko.observableArray();
		
		self.updateAvailableBlocktypes = function(){
			self.availableBlocktypes.removeAll();
			ko.utils.arrayForEach(self.blocktype.blocktypes(),function(blocktype){
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
		self.updateAvailableBlocktypes();
		
		if(self.childCount > 0)
		{
			$.getJSON(site.ajaxurl+'blocks/getchildren',{id:self.id},function(reply){
				self.loading(false);
				if(reply.success)
				{
					for(var i=0;i<reply.blocks.length;i++)
					{
						self.blocks.push(new block(reply.blocks[i]));
					}
					self.updateAvailableBlocktypes();
				}
				else
				{
					site.tellUser(reply);
				}
			});
		}
		else
		{
			self.loading(false);
		}
		
		for(var i=0;i<data.files.length;i++)
		{
			self.files.push(new file(data.files[i]));
		}
		
		self.addfiles = function(files) {
			if(self.blocktype.filelimit() == 1)
			{
				self.files.removeAll();
			}
			for(var i=0;i<files.length;i++)
			{
				self.files.push(files[i]);
			}
		};
		
		self.removeFile = function(file) {
			self.files.remove(file);
			return false;
		};
		
		/*if(self.blocktype.type == 'gallery')
		{
			// Would it be better to have the server add the existing files? This might freeze up if there are 10+ big slideshows on 1 page
			$.getJSON(site.ajaxurl+'blocks/files/'+self.id, function(reply){
				if(reply.success)
				{
					var files = $.parseJSON(reply.files);
					for(var i=0;i<files.length;i++)
					{
						self.files.push(new file(files[i]));
					}
				}
				else
				{
					site.tellUser(reply);
				}
			});
		}*/
		
		/*if(self.blocktype.type == 'complex')
		{
			$.post(site.ajaxurl+'content/getblocks', {id:self.contentid, parent:self.id}, function(reply){
				if(reply.success)
				{
					for(var i=0;i<reply.blocks.length;i++)
					{
						var $view = $(reply.blocks[i].view);
						$elem.find('.kids').first().append($view);
						var blockModel = new block(reply.blocks[i], $view, contentModel);
						ko.applyBindings(blockModel, $view[0]);
						self.blocks.push(blockModel);
					}
				}
			}, 'json');
		}*/
		
		self.addBlock = function(elem, ev){
			if ($(ev.target).prev('.blocktypes').val() != 0)
			{
				$.post(site.ajaxurl+'blocks/add', {
					'blocktype_id' : $(ev.target).prev('.blocktypes').val(),
					'content_id' : self.contentid,
					'parent' : self.id
				}, function(reply) {
					if (reply.success)
					{
						self.blocks.push(new block(reply.block));
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
		
		self.sortChildBlocks = function(elem, ui){
			var i = 0;
			var blocks = {};
			$(elem).find('.kids:first > .block').each(function(){
				var block = ko.dataFor(this);
				block.order = i;
				blocks[block.id] = i;
				i++;
			});
			$.post(site.ajaxurl + '/blocks/setorder', blocks);
		};
		
		self.toggleOpen = function(){
			if(self.collapsed() == '1')
			{
				self.collapsed('0');
			}
			else
			{
				self.collapsed('1');
			}
			$.post(site.ajaxurl+'blocks/collapse',{id:self.id,collapsed:self.collapsed()=='1'?'1':'0'});// We don't care if it succeed or not really
		};
		
		self.removeBlock = function(block, ev)
		{
			if(confirm('Er du sikker på du vil slette denne blok?'))
			{
				// Max blocks and the select boxes vars
				$.post(site.ajaxurl+'blocks/delete',
				{
					'block_id' : block.id
				}, function(reply)
				{
					if (reply.success)
					{
						// After removing the block, the number of blocks is under the max number of block
						// So show the option in the dropdown
						/*if (self.blocktype.max > 0)
						{
							if ((self.currentblocks - 1) < self.max)
							{
								self.$blocktypes.find('option[value="' + self.blocktypeid + '"]').show();
							}
						}*/
						// Remove the block
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
	};
	return block;
});
