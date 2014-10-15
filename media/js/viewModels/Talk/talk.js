define([
	'knockout',
	'jquery',
	'validate',
	'models/comment'
],function(ko,$, validate, comment){
	
	var talkModel = function(){
		
		
		
	};
	
	$('.comments .comment').each(function(){
		ko.applyBindings(new comment($(this)), $(this)[0]);
	});
	
	//ko.applyBindings(new talkModel(), $('section.main')[0]);
	
});
