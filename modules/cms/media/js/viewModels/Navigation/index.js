define([
	'jquery',
	'knockout',
	'site',
	'models/Navigation/menu',
	'models/Navigation/menuitem',
	'models/Content/contenttype'
],function($, ko, site, menu, menuitem, contenttype){
	
	return function(){
		var self = this;
		
		self.menu = ko.observable(false);
		self.menuid = ko.observable(0);
		self.contenttype = ko.observable(false);
		self.contenttypeid = ko.observable(0);
		self.contentid = ko.observable(0);
		self.dirty = ko.observable(false); // Hmm...
		self.externalitem = ko.observable({
			url:ko.observable(''),
			linktext:ko.observable(''),
			titletext:ko.observable('')
		});
		self.showAddMenuOptions = ko.observable(false);
		self.menus = ko.observableArray();
		self.menutypes = ko.observableArray();
		self.editingMenuTitle = ko.observable(false);
		
		$.getJSON(site.ajaxurl+'navigation/info',function(reply){
			if(reply.success)
			{
				for(var i=0;i<reply.menus.length;i++)
				{
					self.menus.push(reply.menus[i]);
				}
				for(var i=0;i<reply.menutypes.length;i++)
				{
					self.menutypes.push(reply.menutypes[i]);
				}
			}
			else
			{
				site.tellUser(reply);
			}
		});
		
		self.addNewMenu = function(){
			if($('#navigation-add-menu-title').val() != '')
			{
				$.post(site.ajaxurl+'navigation/addnewmenu',{name:$('#navigation-add-menu-title').val()},function(reply){
					if(reply.success)
					{
						self.menus.push(reply.menu);
						self.menuid(reply.menu.id);
					}
					site.tellUser(reply);
				}, 'json');
				self.showAddMenuOptions(false);
			}
		};
		
		self.addExternalMenuItem = function(){
			var item = {
				id:'',
				content:{
					id:'',
					title:self.externalitem().linktext()
				},
				linktext:self.externalitem().linktext(),
				titletext:self.externalitem().titletext(),
				classes:'',
				order:0,
				parent:0,
				url:self.externalitem().url(),
				children:[],
				type:'external'
			};
			self.menu().items.push(new menuitem(item));
			self.dirty(true);
			self.externalitem().url('');
			self.externalitem().linktext('');
			self.externalitem().titletext('');
		};
		
		self.addInternalMenuItem = function(){
			var id = $('#navigation-select-content').val();
			var content = ko.utils.arrayFirst(self.contenttype().items,function(item){
				return item.id == id;
			});
			var item = {
				id:'',
				content:content,
				linktext:content.title,
				titletext:content.title,
				classes:'',
				order:0,
				parent:0,
				children:[],
				type:'content'
			};
			self.menu().items.push(new menuitem(item));
			self.dirty(true);
		};
		
		self.deleteMenuItem = function(item){
			self.menu().items.remove(item);
		};
		
		self.editMenuTitle = function(){
			self.editingMenuTitle(true);
		};
		
		self.saveMenuTitle = function(){
			$.post(site.ajaxurl+'navigation/savemenutitle',{
				id:self.menu().id,
				title:self.title()
			}, function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
				self.editingMenuTitle(false);
			});
		};
		
		self.deleteMenu = function(){
			if(confirm('Er du sikker på du vil slette menuen?'))
			{
				var id = self.menu().id;
				$.post(site.ajaxurl+'navigation/deletemenu',{id:id},function(reply){
					if(reply.success)
					{
						var menu = ko.utils.arrayFirst(self.menus(),function(m){
							return m.id == id;
						});
						self.menus.remove(menu);
						self.menuid('');
					}
					else
					{
						site.tellUser(reply);
					}
				},'json');
			}
		};
		
		self.saveMenu = function(){
			var items = [];
			$('#navigation-menu-list').find('> .widget').each(function(index, menuitem){
				var item = ko.dataFor(menuitem);
				items.push({
					id:item.id,
					content_id:item.content.id,
					menu_id:self.menu().id,
					type:item.type,
					url:item.url(),
					classes:item.classes,
					linktext:item.linktext(),
					titletext:item.titletext(),
					kids:item.getKids(menuitem, self.menu()),
					parent:0,
					order:index
				});
			});
			$.post(site.ajaxurl+'navigation/savemenu',{
				id:self.menuid,
				menutype:self.menu().menutype_id(),
				title:self.menu().title(),
				items:items
			},function(reply){
				if(reply.success)
				{
					self.dirty(false);
				}
				site.tellUser(reply);
			}, 'json');
		};
		
		self.getRootMenuItems = ko.computed(function(){
			if(!self.menu())
			{
				return [];
			}
			return ko.utils.arrayFilter(self.menu().items(), function(item){
				return item.parent() == 0;
			});
		});
		
		self.getChildMenuItems = function(menuitem){
			return ko.utils.arrayFilter(self.menu().items(), function(item){
				return item.parent() == menuitem.id;
			});
		};
		
		self.menuid.subscribe(function(newval){
			if(newval != 0 && newval)
			{
				$.getJSON(site.ajaxurl+'navigation/get',{id:newval},function(reply){
					if(reply.success)
					{
						var menutypeid = reply.menu.menutype_id;
						if(menutypeid == 0)
						{
							menutypeid = undefined;
						}
						self.menu(new menu(reply.menu));
						self.menu().menutype_id(menutypeid);
					}
					else
					{
						site.tellUser(reply);
					}
				});
			}
			else
			{
				self.menu(false);
			}
		});
		
		self.contenttypeid.subscribe(function(newval){
			if(newval != 0)
			{
				$.getJSON(site.ajaxurl+'contenttype/get',{id:newval},function(reply){
					if(reply.success)
					{
						self.contenttype(new contenttype(reply));
					}
					else
					{
						site.tellUser(reply);
					}
				});
			}
			else
			{
				self.contenttype(false);
			}
		});
		
	};
	
});
