define(['knockout', 'jquery'],function(ko, $){
	
	var day = function(){
		var self = this;
		
		self.content = ko.observable('');
		
		self.savetimer = setInterval(function(){
			$.post(ajaxurl+'/pages/autosave', {
				'id':$('#page-content').data('id'),
				'content':$('#morningpage').val()
			}, function(reply){
				if(!reply.success)
				{
					// Do something?
					console.log(reply);
				}
			}, 'json');
		}, 10000);
		
		self.autosave = function(){
			
		};
		
	};
	
	return day;
	
});
