require.config({
	'paths':{
		'knockout':'vendor/knockout',
		'jquery':'//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min',
		'autogrow':'vendor/jquery.autosize',
		'validate':'vendor/jquery.validate.min',
		'jgrowl':'vendor/jgrowl.min'
	},
	'shim':{
		'autogrow':{
			'deps':['jquery']
		},
		'validate':{
			'deps':['jquery']
		},
		'jgrowl':{
			'deps':['jquery']
		}
	}
});

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
		
		self.hamburgerClick = function(){
			$('#user-options-triangle').show();
			$( "#user-options" ).slideToggle( "slow", function() {
				// Animation complete.
				if(!$('#user-options').is(':visible'))
				{
					$('#user-options-triangle').hide();
				}
			});
		};
		
		self.saveUserInfo = function(){
			console.log('save');
			self.user.saveInfo().then(function(data){
				self.optionsmodal.hide();
				site.say('Your settings have been saved.');
			});
		};
		
		self.goToPreviousPage = function(obg, ev){
			var date = $(ev.target).val();
			if($(ev.target).val() != 0)
			{
				window.location.href = '/write/'+date;
			}
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
		
		/*var savetimer = setInterval(function(){
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
		}, 10000);*/
		
	};
	
	ko.applyBindings(new writeModel());
	
});

