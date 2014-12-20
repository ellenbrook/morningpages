define([
	'jquery',
	'knockout',
	'site',
	'models/Blocks/meta'
], function($, ko, site, meta){
	
	var blocktype = function(info){
		var self = this;
		
		self.blocktypes = ko.observableArray();
		self.open = ko.observable(false);
		
		self.id = info.id;
		self.parent = info.parent;
		self.contenttype_id = info.contenttype_id;
		self.contenttypetype_id = info.contenttypetype_id;
		self.type = info.type;
		self.key = ko.observable(info.key);
		self.display = ko.observable(info.display);
		self.min = ko.observable(info.min);
		self.max = ko.observable(info.max);
		self.metas = ko.observableArray();
		self.contents = ko.observableArray();
		self.filelimit = ko.observable(0);
		
		for(var i=0;i<info.children.length;i++)
		{
			self.blocktypes.push(new blocktype(info.children[i]));
		}
		
		for(var i=0;i<info.meta.length;i++)
		{
			self.metas.push(new meta(info.meta[i]));
			if(info.meta[i].key == 'filelimit')
			{
				self.filelimit(info.meta[i].values);
			}
		}
		
		
		for(var i=0;i<info.contents.length;i++)
		{
			self.contents.push(info.contents[i]);
		}
		
		self.getMeta = function(key){
			var meta = ko.utils.arrayFirst(self.metas(), function(meta){
				return meta.key() == key;
			});
			return meta.values;
		};
		
		self.toggleOpen = function(){
			self.open(!self.open());
		};
		
		self.addBlocktype = function(){
			$('#super-add-blocktype-modal').find('.add-blocktype').off('click').on('click', function(){
				$.post(site.ajaxurl+'super/addblocktype',{
					parent:self.id,
					contenttypeid:self.contenttype_id,
					contenttypetypeid:self.contenttypetype_id,
					type:$('#super-add-blocktype-type').val(),
					key:$('#super-add-blocktype-key').val(),
					display:$('#super-add-blocktype-display').val(),
					min:$('#super-add-blocktype-min').val(),
					max:$('#super-add-blocktype-max').val()
				},function(reply){
					if(reply.success)
					{
						$('#super-add-blocktype-key').val('');
						$('#super-add-blocktype-display').val('');
						$('#super-add-blocktype-min').val(0);
						$('#super-add-blocktype-max').val(0);
						self.blocktypes.push(new blocktype(reply.blocktype));
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
				$('#super-add-blocktype-modal').modal('hide');
			});
			$('#super-add-blocktype-modal').modal('show');
		};
		
		self.deleteBlocktype = function(blocktype){
			if(confirm('Sikker på du vil slette denne blocktype samt eksisterende blocks?'))
			{
				$.post(site.ajaxurl+'super/deleteblocktype',{id:blocktype.id},function(reply){
					if(reply.success)
					{
						self.blocktypes.remove(blocktype);
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
		};
		
		self.saveBlocktype = function(blocktype){
			$.post(site.ajaxurl+'super/saveblocktype',{
				id:blocktype.id,
				key:blocktype.key(),
				display:blocktype.display(),
				min:blocktype.min(),
				max:blocktype.max()
			},function(reply){
				site.tellUser(reply);
			},'json');
		};
		
		self.addMeta = function(){
			$('#super-add-blocktype-meta-modal').find('.add-blocktype-meta').off('click').on('click', function(){
				$.post(site.ajaxurl+'super/addmeta',{
					'blocktype_id':self.id,
					'key':$('#super-add-blocktype-meta-key').val(),
					'value':$('#super-add-blocktype-meta-value').val()
				}, function(reply){
					if(reply.success)
					{
						$('#super-add-blocktype-meta-key').val('');
						$('#super-add-blocktype-meta-value').val('');
						self.metas.push(new meta(reply.meta));
						$('#super-add-blocktype-meta-modal').modal('hide');
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			});
			$('#super-add-blocktype-meta-modal').modal('show');
		};
		
		self.deleteMeta = function(meta){
			if(confirm('Sikker?'))
			{
				$.post(site.ajaxurl+'super/deletemeta',{id:meta.id},function(reply){
					if(reply.success)
					{
						self.metas.remove(meta);
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
		};
		
		self.sortBlocktypes = function(elem){
			var order = {};
			var i = 0;
			$(elem).find('> .block').each(function(){
				order[ko.dataFor($(this)[0]).id] = i;
				i++;
			});
			$.post(site.ajaxurl+'super/sortblocktypes',order, function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.duplicateBlocktype = function(elem){
			$.post(site.ajaxurl+'super/duplicateblocktype',{id:self.id},function(reply){
				if(reply.success)
				{
					self.blocktypes.push(new blocktype(reply.blocktype));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
	};
	
	return blocktype;
	
});
