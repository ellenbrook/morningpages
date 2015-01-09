define([
	'jquery',
	'knockout',
	'site',
	'models/Content/contenttypetype'
], function($, ko, site, contenttypetype){
	
	return function(info){
		var self = this;
		
		self.open = ko.observable(false);
		
		self.id = info.id;
		self.type = ko.observable(info.type);
		self.slug = ko.observable(info.slug);
		self.display = ko.observable(info.display);
		self.icon = ko.observable(info.icon);
		self.max = ko.observable(info.max);
		self.contenttypetypes = ko.observableArray();
		self.items = info.items;
		
		if(info.contenttypetypes)
		{
			for(var i=0;i<info.contenttypetypes.length;i++)
			{
				self.contenttypetypes.push(new contenttypetype(info.contenttypetypes[i]));
			}
		}
		
		self.supports = {
			categories:ko.observable(info.has_categories=="1"?1:0),
			timestamp:ko.observable(info.has_timestamp=="1"?1:0),
			tags:ko.observable(info.has_tags?1:0),
			thumbnail:ko.observable(info.has_thumbnail=="1"?1:0),
			hierarchy:ko.observable(info.hierarchical=="1"?1:0),
			author:ko.observable(info.has_author=="1"?1:0),
			status:ko.observable(info.has_status=="1"?1:0)
		};
		
		self.toggleOpen = function(){
			self.open(!self.open());
		};
		
		self.addContenttypetype = function(){
			$.post(site.ajaxurl+'super/addcontenttypetype',{
				id:self.id,
				key:$('#super-add-contenttypetype-key').val(),
				display:$('#super-add-contenttypetype-display').val(),
				min:$('#super-add-contenttypetype-min').val(),
				max:$('#super-add-contenttypetype-max').val(),
			}, function(reply){
				if(reply.success)
				{
					$('#super-add-contenttypetype-key').val('');
					$('#super-add-contenttypetype-display').val('');
					$('#super-add-contenttypetype-min').val(0);
					$('#super-add-contenttypetype-max').val(0);
					self.contenttypetypes.push(new contenttypetype(reply.contenttypetype));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.saveContenttypetypesOrder = function(elem){
			var i = 0;
			var orders = {};
			$(elem).find('.block').each(function(index, $contenttypetype){
				var contenttypetype = ko.dataFor($contenttypetype);
				orders[contenttypetype.id] = i;
				i++;
			});
			$.post(site.ajaxurl+'super/savecontenttypetypeorders',orders,function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.deleteContenttypetype = function(typetype){
			if(confirm('Sikker på du vil slette contenttyptype, blocktypes samt content?'))
			{
				$.post(site.ajaxurl+'super/deletecontenttypetype',{id:typetype.id},function(reply){
					if(reply.success)
					{
						self.contenttypetypes.remove(typetype);
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
		self.save = function(){
			$.post(site.ajaxurl+'super/editcontenttype',{
				'id':self.id,
				'type':self.type(),
				'slug':self.slug(),
				'icon':self.icon(),
				'display':self.display(),
				'max':self.max(),
				'hierarchical':self.supports.hierarchy(),
				'categories':self.supports.categories(),
				'tags':self.supports.tags(),
				'timestamp':self.supports.timestamp(),
				'thumbnail':self.supports.thumbnail(),
				'author':self.supports.author(),
				'status':self.supports.status()
			},function(reply){
				if(reply.success)
				{
					site.tellUser({type:'success','message':'Gemt'});
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
			
		};
		
		self.duplicateContenttypetype = function(elem){
			$.post(site.ajaxurl+'super/duplicatecontenttypetype',{id:elem.id},function(reply){
				if(reply.success)
				{
					self.contenttypetypes.push(new contenttypetype(reply.contenttypetype));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
	};
	
});
