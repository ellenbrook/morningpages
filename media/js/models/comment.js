define([
	'jquery',
	'knockout',
	'site',
	'autogrow'
],function($, ko, site){
	
	var commentModel = function($element, talkModel){
		var self = this;
		
		self.element = $element;
		self.contentarea = $element.find('.completearea');
		self.editarea = self.element.find('.editarea');
		self.id = $element.data('id');
		self.type = $element.hasClass('op') ? 'talk' : 'talkreply';
		self.editing = false;
		self.originalhtml = self.contentarea.html();
		self.editbutton = self.element.find('.editbutton');
		self.authorname = ko.observable(self.element.find('.comment-author').text().trim());
		
		self.edit = function(elem, ev){
			if(!self.editing)
			{
				$(self.editbutton).attr("disabled", "disabled");
				self.editbutton.find('span').removeClass('fa-pencil').addClass('fa-spin fa-spinner');
				$.getJSON('/ajax/talk/rawform',{
					'id':self.id
				},function(reply){
					if(reply.success)
					{
						self.editing = true;
						self.element.find('.comment-content').hide();
						self.editarea.find('textarea').val(reply.raw).autosize();
						self.editarea.show();
					}
					self.editbutton.find('span').removeClass('fa-spin fa-spinner').addClass('fa-pencil');
				});
			}
			else
			{
				var val = self.editarea.find('textarea').val().trim();
				if(val.length > 0 && val.length < 1001)
				{
					$.post('/ajax/talk/edit',{
						'id':self.id,
						'value':val
					},function(reply){
						if(reply.success)
						{
							self.contentarea.html(reply.post).show();
							self.originalhtml = reply.post;
							self.editarea.hide();
							self.editarea.find('textarea').val('').trigger('autosize.destroy');
							self.editbutton.removeAttr('disabled');
							self.editing = false;
						}
						site.say(reply);
					},'json');
				}
				else if(val.length > 1000)
				{
					site.say('Your post must be under 1000 characters (right now it\'s '+val.trim().length+' characters long)');
				}
				else
				{
					site.say('You can\'t post an empty message.');
				}
			}
		};
		
		self.canceledit = function(){
			self.contentarea.show();
			self.editarea.find('textarea').val('').trigger('autosize.destroy');
			self.editarea.hide();
			self.editbutton.removeAttr('disabled');
			self.editing = false;
		};
		
		self.quote = function(){
			site.scrollTo($('#replyform'));
			talkModel.replyto_id(self.id);
			talkModel.replyto(self.authorname());
			$('#replyform').find('.loader').show();
			$.getJSON('/ajax/talk/quoteform',{
				'id':self.id,
				'type':self.type
			},function(reply){
				if(reply.success)
				{
					$('#new-reply-content').val(reply.quote+"\r\n").trigger('autosize.resize').focus();
				}
				$('#replyform').find('.loader').hide();
			});
		};
		
		self.comment = function(){
			site.scrollTo($('#replyform'));
		};
		
		self.vote = function(elem, ev){
			$.post('/ajax/talk/vote',{
				'id':self.id,
				'type':self.type
			}, function(reply){
				if(!reply.success)
				{
					site.say(reply);
				}
				else
				{
					var $votes = $element.find('.votes');
					if(reply.message == 'add')
					{
						var old = parseInt($votes.text());
						$votes.text(old+1);
						$(ev.target).addClass('voted');
					}
					if(reply.message == 'remove')
					{
						var old = parseInt($votes.text());
						var newvotes = old-1;
						if(newvotes < 0) newvotes = 0;
						$votes.text(newvotes);
						$(ev.target).removeClass('voted');
					}
				}
			}, 'json');
		};
		
	};
	
	return commentModel;
	
});
