define([
	'knockout',
	'jquery',
	'site',
	'validate',
	'markdown'
],function(ko,$, site, validate, markdown){
	
	var talkModel = function(){
		
		self.submitTalk = function(){
			console.log('valid');
		};
		
		self.site = site;
		
	};
	
	ko.applyBindings(new talkModel(), $('section.main')[0]);
	
});
