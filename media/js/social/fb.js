define(['facebook'], function(){
	
	var fbModel = function(){
		var self = this;
		
		self.logged = false;
		
		FB.init({
			//appId:'874456799244937' // Production-dev: dev.morningpages.net
			appId:'874461365911147' // Testapp: morningpages.dev
		});
		
		self.init = function(){
			return $.Deferred(function(defer){
				FB.getLoginStatus(function(reply){
					if(reply.status == 'connected')
					{
						self.logged = true;
						defer.resolve(self);
					}
					else
					{
						defer.reject(self);
					}
				});
			});
		};
		
		self.login = function(elem, event){
			return $.Deferred(function(defer){
				FB.login(function(reply){
					if(reply.status == 'connected')
					{
						defer.resolve(reply);
					}
					else
					{
						defer.reject(reply);
					}
				},{scope:'public_profile,email'});
			});
		};
		
		self.deleteme = function(){
			FB.api('/me/permissions', 'delete', function(reply){
				// hmm
			});
		};
		
		self.logout = function(elem, event){
			FB.logout();
		};
		
		self.api = function(endpoint){
			return $.Deferred(function(defer){
				FB.api(endpoint, function(reply){
					defer.resolve(reply);
				});
			});
		};
		
		
	};
	
	return new fbModel();
	
});
