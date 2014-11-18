define([
	'knockout',
	'jquery',
	'validate',
	'markdown'
],function(ko,$, validate, markdown){
	
	var talkModel = function(){
		
		self.submitTalk = function(){
			console.log('valid');
		};
		
	};
	
	ko.applyBindings(new talkModel(), $('section.main')[0]);
	
});
