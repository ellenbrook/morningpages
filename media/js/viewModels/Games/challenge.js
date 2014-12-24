define([
	'knockout',
	'jquery',
	'site'
], function(ko, $, site){
	
	var challengeModel = function(){
		var self = this;
		
		self.user = site.user;
		self.site = site;
		
		self.confirmSignup = function(){
			if(confirm('Are you sure you want to sign up for the 30 day challenge?'))
			{
				$.get('/ajax/games/takechallenge', function(reply){
					if(reply.success)
					{
						self.site.user.doingChallenge(true);
						self.site.user.challengeProgress(parseInt(reply.progress));
					}
					self.site.say(reply);
				},'JSON');
			}
			return false;
		};
		
	};
	
	ko.applyBindings(new challengeModel, $('.main')[0]);
	
});
