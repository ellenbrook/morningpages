define(['knockout', 'jquery','site'],function(ko, $, site){
	
	var note = function(){
		
	};
	
	var popnotes = function(){
		var self = this;
		
		self.notes = ko.observableArray();
		
		$.getJSON('/ajax/js/notes', function(reply){
			if(reply.notes.length > 0)
			{
				for(var i=0;i<reply.notes.length;i++)
				{
					site.say(reply.notes[i]);
				}
				//site.say(reply.notes);
			}
		});
		
	};
	
	return new popnotes();
	
});
