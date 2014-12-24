define([
	'knockout'
], function(ko){
	
	var leaderboardModel = function(){
		var self = this;
	};
	
	//return new contactModel();
	ko.applyBindings(new leaderboardModel, $('.main')[0]);
	
});

