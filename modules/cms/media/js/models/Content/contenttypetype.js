define([
	'jquery',
	'knockout',
	'site',
	'models/Content/blocktype'
], function($, ko, site, blocktype){
	
	return function(info){
		var self = this;
		
		self.id = info.id;
		self.key = ko.observable(info.key);
		self.display = ko.observable(info.display);
		self.min = ko.observable(info.min);
		self.max = ko.observable(info.max);
		self.blocktypes = ko.observableArray();
		self.contenttype_id = info.contenttype_id;
		self.open = ko.observable(false);
		
		for(var i=0;i<info.blocktypes.length;i++)
		{
			self.blocktypes.push(new blocktype(info.blocktypes[i]));
		}
		
		self.toggleOpen = function(){
			self.open(!self.open());
		};
		
		self.save = function(){
			$.post(site.ajaxurl+'super/savecontenttypetype',{
				id:self.id,
				key:self.key(),
				display:self.display(),
				min:self.min(),
				max:self.max()
			},function(reply){
				site.tellUser(reply);
			}, 'json');
		};
		
		self.addBlocktype = function(){
			$('#super-add-blocktype-modal').find('.add-blocktype').off('click').on('click', function(){
				$.post(site.ajaxurl+'super/addblocktype',{
					parent:0,
					contenttypeid:self.contenttype_id,
					contenttypetypeid:self.id,
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
			$.post(site.ajaxurl+'super/duplicateblocktype',{id:elem.id},function(reply){
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
	
});
