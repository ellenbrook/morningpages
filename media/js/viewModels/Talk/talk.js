define([
	'knockout',
	'jquery',
	'site',
	'validate',
	'models/comment',
	'models/autosize'
],function(ko,$, site, validate, comment, autosize){
	
	var replyformModel = function(){
		var self = this;
		
		self.replyto_id = ko.observable(0);
		self.replyto = ko.observable('');
		
		self.site = site;
		
		$('.comments .comment').each(function(){
			ko.applyBindings(new comment($(this), self), $(this)[0]);
		});
		
		self.cancelreply = function(){
			self.replyto('');
			self.replyto_id(0);
		};
		
		self.subscribe = function(elem, ev){
			$.post('/ajax/talk/subscribe',{id:$(ev.target).val()},function(reply){
				site.say(reply);
			}, 'json');
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
