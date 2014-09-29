define([
	'knockout',
	'jquery',
	'site',
	'models/user',
	'models/modal',
	'models/autosave'
], function(ko, $, site, user, modal, autosave){
	
	var writeModel = function(){
		var self = this;
		
		self.user = new user();
		self.user.getInfo().then(function(){
			self.writtenwords('');
			if(self.user.options.hemingwaymode())
			{
				$('#morningpage-content').on('keydown',function(e){
					if(e.keyCode == 8 || e.keyCode == 46)
					{
						return false;
					}
				});
			}
		});
		
		self.autosaver = new autosave($('#morningpage-content'));
		self.autosaver.get().then(function(reply){
			self.autosaver.element.val(reply.content);
		});
		
		self.submitPage = function(){
			if(!self.user.logged())
			{
				alert('Log in asshole');
				return false;
			}
		};
		
		self.optionsmodal = new modal($('#user-options-modal'));
		self.shortcutsmodal = new modal($('#shortcuts-modal'));
		
		self.writtenwords = ko.observable('');
		self.totalwords = ko.observable(0);
		self.wordcount = ko.computed(function(){
			var total = $.trim(self.writtenwords()).split(/\s+/).length;
			if(self.writtenwords().length == 0) total = 0;
			total += parseInt(self.user.wordcount());
			self.totalwords(total);
			return total;
		}, this);
		
		self.switchTheme = function(obj, ev){
			$('#csstheme').attr('href','media/css/themes/'+$(ev.target).val()+'.css');
		};
		
		$(document).on("keydown", function(e){
			if(e.ctrlKey && e.keyCode == 32)
			{
				$('#page-content').toggle();
				$('#writeform').toggle();
				$('#dummy-content').toggle();
				$('#sidebar').toggle();
				$('#header').toggle();
				$('#user-options').hide();
			}
		});
		
		self.saveUserInfo = function(){
			console.log('save');
			self.user.saveInfo().then(function(data){
				self.optionsmodal.hide();
				site.say('Your settings have been saved.');
			});
		};
		
	};
	
	ko.applyBindings(new writeModel(),$('#writing-container')[0]);
	
});

