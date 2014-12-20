define([
	'jquery',
	'knockout',
	'site',
	'models/Users/user',
	'models/Users/role'
], function($, ko, site, user, role){
	
	return function(data){
		var self = this;
		
		self.users = ko.observableArray();
		self.limit = ko.observable(data.limit);
		self.page = ko.observable(data.page+1);
		self.userCount = ko.observable(data.userCount);
		self.roles = ko.observableArray();
		self.loading = ko.observable(false);
		self.role = data.role;
		
		for(var i=0;i<data.users.length;i++)
		{
			self.users.push(new user(data.users[i]));
		}
		for(var i=0;i<data.roles.length;i++)
		{
			self.roles.push(new role(data.roles[i]));
		}
		
		self.showAddRoleModal = function(){
			$('#users-add-role-modal').modal('show');
		};
		
		self.addRole = function(){
			$('#users-add-role-modal').modal('hide');
			var name = $('#users-add-role-modal-name').val();
			var description = $('#users-add-role-modal-description').val();
			if(name != '')
			{
				$.post(site.ajaxurl+'roles/create',{name:name,description:description},function(reply){
					if(reply.success)
					{
						$('#users-add-role-modal-name').val('');
						$('#users-add-role-modal-description').val('');
						self.roles.push(new role(reply.role));
					}
					else
					{
						site.tellUser(reply);
					}
				}, 'json');
			}
			else
			{
				site.tellUser('Udfyld et navn for at tilføje en ny rolle');
			}
		};
		
		self.deleteRole = function(role){
			if(confirm('Er du sikke på du vil slette denne rolle?'))
			{
				$.post(site.ajaxurl+'roles/delete',{id:role.id}, function(reply){
					if(reply.success)
					{
						self.roles.remove(role);
					}
					site.tellUser(reply);
					site.redirect('#/users');
				},'json');
			}
		};
		
	};
	
});
