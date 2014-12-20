define([
	'jquery',
	'knockout',
	'site',
	'models/Messages/message'
], function($, ko, site, message){
	
	return function(data){
		var self = this;
		
		self.messages = ko.observableArray();
		
		for(var i=0;i<data.messages.length;i++)
		{
			self.messages.push(new message(data.messages[i]));
		}
		
	};
	
});
