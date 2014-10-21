define([
	'jquery',
	'knockout',
	'site',
	'plugins/refill',
	'models/modal'
],function($, ko, site, refill, modal){
	
	var optionsModel = function(){
		var self = this;
		
		self.user = site.user;
		self.user.getInfo();
		
		self.deleteModal = new modal( $('#delete-account-modal') );
		ko.applyBindings(self.deleteModal, $('#delete-account-modal')[0]);
		
		self.save_setting = function(setting){
			self.user.save_setting(setting).done(function(reply){
				reply = $.parseJSON(reply);
				site.say(reply);
			});
		};
		
		self.deleteAccount = function(){
			self.deleteModal.show().done(function(){
				window.location.href='/user/delete';
			}).fail(function(){
				site.say('Glad you decided to stick around! <span class="fa fa-smile-o"></span>');
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
