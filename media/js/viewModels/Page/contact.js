define([
	'knockout'
], function(ko){
	
	var contactModel = function(){
		var self = this;
	};
	
	//return new contactModel();
	ko.applyBindings(new contactModel, $('#contact-page')[0]);
	
});

