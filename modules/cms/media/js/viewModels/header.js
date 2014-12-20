define([
	'jquery',
	'knockout'
], function($, ko){
	
	return function(site){
		
		var self = this;
		
		self.current_visitors = ko.observable(0);
		self.unread_messages = ko.observable(0);
		self.firstload = true;
		self.loading = ko.observable(false);
		
		$('#header-filesbtn').click(function(){
			site.filebrowser().show();
			return false;
		});
		
		var pop = new Audio(site.url+'media/sounds/notification.ogg');
		
		self.info = function(data){
			
			self.current_visitors(data.current_visitors);
			if(data.unread_messages > self.unread_messages())
			{
				site.tellUser('<a href="#/messages">Du har nye beskeder</a>', 'warning');
				if((new Audio()).canPlayType("audio/ogg; codecs=vorbis"))
				{
					pop.play();
				}
			}
			self.unread_messages(data.unread_messages);
		};
		
		self.decrementUnreadMessages = function(){
			var prev = self.unread_messages();
			var next = prev - 1;
			if(next < 0)
			{
				next = 0;
			}
			self.unread_messages(next);
		};
		self.incrementUnreadMessages = function(){
			var prev = self.unread_messages();
			var next = prev + 1;
			self.unread_messages(next);
		};
		
		self.logout = function(){
			window.location.href="/mpma/bruger/logud";
		};
		
	};
	
});
