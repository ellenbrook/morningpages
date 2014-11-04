define([
	'knockout',
	'jquery'
],function(ko, $){
	
	var meModel = function(){
		var self = this;
	};
	
	ko.applyBindings(new meModel(),$('#mecont')[0]);
	
});
