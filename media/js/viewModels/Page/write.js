define([
	'knockout',
	'jquery',
	'bindings',
	'site',
	'models/user',
	'models/modal',
	'models/autosave'
], function(ko, $, bindings, site, user, modal, autosave){
	
	var writeModel = function(){
		var self = this;
		
		self.user = new user();
		self.user.getInfo().then(function(){
			self.writtenwords('');
		});
		
		self.autosaver = new autosave();
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

		$('.modal-tabbed-nav ul li').click(function(e){
			e.preventDefault();
			$('.modal-tabbed-nav ul li.active-tab').removeClass('active-tab');
			$(this).addClass('active-tab');
			$('.user-options-modal-body').hide();

			//gross and hacky but it works for now
			if ($('.active-tab a').attr('href') == "personal-settings") {
				$('.personal-settings').show();
			} else if ($('.active-tab a').attr('href') == "change-password") {
				$('.change-password').show();
			} else if ($('.active-tab a').attr('href') == "delete-account") {
				$('.delete-account').show();
			}

		});
		
	};
	
	ko.applyBindings(new writeModel());
	
});

