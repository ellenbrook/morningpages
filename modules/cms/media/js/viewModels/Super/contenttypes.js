define([
	'jquery',
	'knockout',
	'site',
	'models/Content/contenttype'
], function($, ko, site, contenttype){
	
	return function(){
		var self = this;
		
		self.contenttypes = ko.observableArray();
		self.loading = ko.observable(true);
		
		self.selectedContenttype = ko.observable(false);
		
		self.sortContenttypes = function(){
			var order = {};
			var i = 0;
			$('#super-contenttypes').find('> .block').each(function(){
				order[ko.dataFor($(this)[0]).id] = i;
				i++;
			});
			$.post(site.ajaxurl+'super/sortcontenttypes',order, function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		$.getJSON(site.ajaxurl+'super/info', function(reply){
			if(reply.success)
			{
				for(var i=0;i<reply.contenttypes.length;i++)
				{
					self.contenttypes.push(new contenttype(reply.contenttypes[i]));
				}
				self.loading(false);
			}
			else
			{
				site.tellUser(reply);
			}
		});
		
		self.showAddContenttypeModal = function(){
			$('#super-add-contenttype-modal').modal('show');
		};
		
		self.addContenttype = function(){
			$.post(site.ajaxurl+'super/addcontenttype',{
				'type':$('#super-add-contenttype-type').val(),
				'slug':$('#super-add-contenttype-slug').val(),
				'display':$('#super-add-contenttype-display').val(),
				'max':$('#super-add-contenttype-type').val(),
				'hierarchical':$('#super-add-contenttype-hierarchical').val(),
				'categories':$('#super-add-contenttype-categories').val(),
				'tags':$('#super-add-contenttype-tags').val(),
				'timestamp':$('#super-add-contenttype-timestamp').val(),
				'thumbnail':$('#super-add-contenttype-thumbnail').val(),
				'author':$('#super-add-contenttype-author').val(),
			},function(reply){
				if(reply.success)
				{
					self.contenttypes.push(new contenttype(reply.contenttype));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
			$('#super-add-contenttype-modal').modal('hide');
		};
		
		self.deleteContenttype = function(type){
			if(confirm('Slet contenttype, contenttypetypes, blocks og content associeret med dette?'))
			{
				$.post(site.ajaxurl+'super/deletecontenttype',{id:type.id},function(reply){
					if(reply.success)
					{
						self.contenttypes.remove(type);
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
		};
		
		self.openEditContenttypeModal = function(type){
			self.selectedContenttype(type);
			
			$('#super-edit-contenttype-type').val(type.type());
			$('#super-edit-contenttype-slug').val(type.slug());
			$('#super-edit-contenttype-display').val(type.display());
			$('#super-edit-contenttype-hierarchical').val(type.supports('hierarchy'));
			$('#super-edit-contenttype-categories').val(type.supports('categories'));
			$('#super-edit-contenttype-tags').val(type.supports('tags'));
			$('#super-edit-contenttype-timestamp').val(type.supports('timestamp'));
			$('#super-edit-contenttype-thumbnail').val(type.supports('thumbnail'));
			$('#super-edit-contenttype-author').val(type.supports('author'));
			
			$('#super-edit-contenttype-modal').modal('show');
		};
		
		self.saveContenttype = function(type){
			$.post(site.ajaxurl+'super/editcontenttype',{
				'id':self.selectedContenttype().id,
				'type':$('#super-edit-contenttype-type').val(),
				'slug':$('#super-edit-contenttype-slug').val(),
				'display':$('#super-edit-contenttype-display').val(),
				'max':$('#super-edit-contenttype-type').val(),
				'hierarchical':$('#super-edit-contenttype-hierarchical').val(),
				'categories':$('#super-edit-contenttype-categories').val(),
				'tags':$('#super-edit-contenttype-tags').val(),
				'timestamp':$('#super-edit-contenttype-timestamp').val(),
				'thumbnail':$('#super-edit-contenttype-thumbnail').val(),
				'author':$('#super-edit-contenttype-author').val(),
			},function(reply){
				if(reply.success)
				{
					self.contenttypes.push(new contenttype(reply.contenttype));
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
			
			$('#super-edit-contenttype-modal').modal('hide');
		};
		
		self.showAddContenttypetypeModal = function(type){
			self.selectedContenttype(type);
			$('#super-add-contenttypetype-modal').modal('show');
		};
		
		self.addContenttypetype = function(){
			self.selectedContenttype().addContenttypetype();
			$('#super-add-contenttypetype-modal').modal('hide');
			self.selectedContenttype(false);
		};
		
	};
	
});
