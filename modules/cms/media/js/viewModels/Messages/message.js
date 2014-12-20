define([
	'jquery',
	'knockout',
	'site',
	'models/Messages/message'
], function($, ko, site, message){
	
	return function(data){
		var self = this;
		
		self.message = new message(data.message);
		
		if(self.message.read() == '0')
		{
			self.message.setRead();
		}
		
		self.deleteMessage = function(){
			
		};
		
	};
	
});
