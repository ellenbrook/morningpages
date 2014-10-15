define([
	'jquery',
	'knockout',
	'site'
],function($, ko, site){
	
	var commentModel = function($element){
		var self = this;
		
		self.element = $element;
		self.id = $element.data('id');
		self.type = $element.hasClass('op') ? 'talk' : 'talkreply';
		
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
						if(newvotes < 1) newvotes = 1;
						$votes.text(newvotes);
						$(ev.target).removeClass('voted');
					}
				}
			}, 'json');
		};
		
	};
	
	return commentModel;
	
});
