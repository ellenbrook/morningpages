define([
	'jquery',
	'knockout',
	'site',
	'models/Users/role'
], function($, ko, site, role){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.name = data.name;
		self.email = data.email;
		self.bio = data.bio;
		self.logins = data.logins;
		self.last_login = data.last_login;
		self.delete = data.delete;
		self.created = data.created;
		self.gravatar = {
			mini:ko.observable(data.gravatar.mini),
			medium:ko.observable(data.gravatar.medium),
		};
		self.roles = ko.observableArray();
		
		for(var i=0;i<data.roles.length;i++)
		{
			self.roles.push(new role(data.roles[i]));
		}
		
		self.addRole = function(newrole){
			$.post(site.ajaxurl+'users/addrole',{id:self.id,role:newrole.id},function(reply){
				if(reply.success)
				{
					self.roles.push(new role(reply.role));
				}
				else
				{
					site.tellUser(reply);
				}
			},'json');
		};
		
		self.removeRole = function(roleobj){
			$.post(site.ajaxurl+'users/removerole',{id:self.id,role:roleobj.id},function(reply){
				if(reply.success)
				{
					self.roles.remove(roleobj);
				}
				else
				{
					site.tellUser(reply);
				}
			},'json');
		};
		
	};
	
});
