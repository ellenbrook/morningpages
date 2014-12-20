define([
	'jquery',
	'knockout',
	'site'
], function($,ko,site){
	
	return function(data){
		var self = this;
		
		self.id = data.id;
		self.display = data.display;
		self.type = data.type;
		
	};
	
});
