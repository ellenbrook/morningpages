define([
	'jquery',
	'knockout',
	'site',
	'models/Users/user',
	'models/Users/role'
], function($, ko, site, user, role){
	
	return function(data){
		var self = this;
		
		self.user = new user(data.user);
		self.roles = ko.observableArray();
		
		for(var i=0;i<data.roles.length;i++)
		{
			self.roles.push(new role(data.roles[i]));
		}
		
		self.deleteaction = ko.observable('transfer');
		
		self.availableRoles = ko.computed(function(){
			var userroles = self.user.roles();
			var roles = [];
			ko.utils.arrayForEach(self.roles(), function(role){
				var exists = ko.utils.arrayFirst(userroles, function(userrole){
					return userrole.id == role.id;
				});
				if(!exists)
				{
					roles.push(role);
				}
			});
			return roles;
		});
		
		self.availableRoles();
		
		self.deleteUser = function(){
			$('#edit-user-delete').modal('hide');
			var transfercontentto = $('#edit-user-delete-content-action-transfer-user').val();
			site.header.loading(true);
			$.post(site.ajaxurl+'users/delete',{
					id:self.user.id,
					action:self.deleteaction(),
					newowner:transfercontentto
				},function(reply){
					site.header.loading(false);
					site.tellUser(reply);
					if(reply.success)
					{
						site.redirect('#/users');
					}
			}, 'json');
		};
		
		self.showDeleteUserModal = function(){
			$('#edit-user-delete').modal('show');
		};
		
		self.addRole = function(){
			var roleid = $('#edit-user-add-role-select').val();
			if(roleid && roleid != '' && roleid != 0)
			{
				$.post(site.ajaxurl+'users/addrole',{id:self.user.id,role:roleid},function(reply){
					if(reply.success)
					{
						self.user.roles.push(new role(reply.role));
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
