define(['knockout', 'jquery'],function(ko, $){
	
	var user = function(){
		var self = this;
	
		self.email = ko.observable();
		self.theme = ko.observable();
		self.reminder = ko.observable();
		self.password = ko.observable('');
		self.passconfirm = ko.observable('');
		self.wordcount = ko.observable(0);
		
		self.wakeUp = function(){
			return $.post('/ajax/User/info',function(reply){
				if(reply.success)
				{
					self.email(reply.email);
					self.theme(reply.theme);
					self.reminder(Boolean(parseInt(reply.reminder))); // Get your shit together javascript
					self.wordcount(reply.wordcount);
				}
			},'json');
		};
		
		self.saveInfo = function(){
			var data = {
				'email':self.email(),
				'theme':self.theme(),
				'reminder':self.reminder(),
				'password':self.password(),
				'password_confirm':self.passconfirm()
			};
			return $.post('/ajax/User/saveInfo',data,null,'json');
		};
	};
	
	return user;
	
});
