define([
	'jquery',
	'knockout',
	'site',
	'plugins/refill'
],function($, ko, site, refill){
	
	var optionsModel = function(){
		var self = this;
		
		self.user = site.user;
		self.user.getInfo();
		
		self.save_setting = function(setting){
			self.user.save_setting(setting).done(function(reply){
				reply = $.parseJSON(reply);
				site.say(reply);
			});
		};
		
		self.save_theme = function(){
			$.post('/ajax/user/savetheme',{id:self.user.options.theme_id()},function(reply){
				if(!reply.success)
				{
					site.say(reply);
				}
				else
				{
					site.theme(reply.theme);
				}
			},'json');
		};
		
		$('#site-options').tabs();
		
		
		
		
	};
	
	ko.applyBindings(new optionsModel(),$('#site-options')[0]);
	
});
