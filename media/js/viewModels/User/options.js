define([
	'jquery',
	'knockout',
	'plugins/refill',
	'models/user'
],function($, ko, refill, user){
	
	var optionsModel = function(){
		var self = this;
		
		self.user = new user();
		self.user.getInfo();
		
		$('#site-options').tabs();
		
	};
	
	ko.applyBindings(new optionsModel(),$('#site-options')[0]);
	
});
