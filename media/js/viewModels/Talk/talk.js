define([
	'knockout',
	'jquery',
	'validate',
	'models/comment',
	'models/autosize'
],function(ko,$, validate, comment, autosize){
	
	var replyformModel = function(){
		var self = this;
		
		self.replyto_id = ko.observable(0);
		self.replyto = ko.observable('');
		
		$('.comments .comment').each(function(){
			ko.applyBindings(new comment($(this), self), $(this)[0]);
		});
		
		self.cancelreply = function(){
			self.replyto('');
			self.replyto_id(0);
		};
		
		new autosize($('#new-reply-content'));
	};
	
	var replyfModel = new replyformModel();
	if($('#replyform').length > 0)
	{
		ko.applyBindings(replyfModel, $('#replyform')[0]);
	}
	
	//ko.applyBindings(new talkModel(), $('section.main')[0]);
	
});
