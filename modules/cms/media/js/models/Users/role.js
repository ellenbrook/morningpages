define([
	'knockout'
], function(ko){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.name = ko.observable(data.name);
		self.realname = data.realname;
		self.description = ko.observable(data.description);
		self.userCount = ko.observable(data.userCount);
		self.deleteable = data.deleteable;
		
	};
	
});
