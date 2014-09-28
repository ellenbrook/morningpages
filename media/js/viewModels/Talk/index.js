define([
	'knockout',
	'jquery',
	'validate'
],function(ko,$, validate){
	
	var talkModel = function(){
		
		self.submitTalk = function(){
			console.log('valid');
		};
		
	};
	
	ko.applyBindings(new talkModel(), $('section.main')[0]);
	
});
