define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.hasreply = data.hasreply;
		self.subject = data.subject;
		self.message = data.message;
		self.created = data.created;
		self.nicedate = data.nicedate;
		self.sender = data.sender;
		self.recipient = data.recipient;
		self.read = ko.observable(data.read);
		
		self.open = ko.observable(false);
		
		self.setUnread = function(){
			self.read('0');
			site.header.incrementUnreadMessages();
		};
		
		self.setRead = function(){
			self.read('1');
			site.header.decrementUnreadMessages();
		};
		
		self.toggleRead = function(){
			if(self.read()=='0')
			{
				self.setRead();
			}
			else
			{
				self.setUnread();
			}
			$.post(site.ajaxurl+'messages/toggleread',{id:self.id,read:self.read()},function(reply){
				if(!reply.success)
				{
					site.tellUser(reply);
				}
			},'json');
		};
		
	};
	
});
