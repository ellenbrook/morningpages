define([
	'knockout',
	'jquery',
	'jgrowl',
	'models/user',
	'models/modal'
],function(ko, $, grr, user, modal){
	
	var site = function(){
		var self = this;
		
		self.user = new user();
		
		self.theme = ko.observable();
		self.theme.subscribe(function(newclass){
			$('body').removeClass().addClass(newclass);
		});
		
		self.loginModal = ko.observable();
		self.registerModal = ko.observable();
		
		self.init = function(userLogged, notes){
			self.user.logged(userLogged);
			self.showNotes(notes);
			
			if(!userLogged)
			{
				self.setupRegisterModal();
			}
			else
			{
				self.getUserInfo();
			}
			
		};
		
		self.getUserInfo = function(){
			self.user.getInfo().done(function(){
				if(self.user.options.privacymode())
				{
					self.startUserPrivacyTimer();
				}
			});
		};
		
		self.startUserPrivacyTimer = function(){
			self.user.privacy.startTimer().progress(function(arg){
					if(arg == 'minutewarning')
					{
						self.say('Your privacysettings will log you out in 1 minute if you do not become active');
					}
				}).done(function(){
					self.say('Your privacysettings will now log you out');
					setTimeout(function(){
						$.get('/ajax/user/logout',{},function(){
							window.location.href = '/';
						});
					},2000);
				}).fail(function(){
					self.startUserPrivacyTimer();
				});
		};
		
		self.setupRegisterModal = function(){
			self.registerModal(new modal($('#registerModal')));
			ko.applyBindings(self.registerModal(), $('#registerModal')[0]);
		};
		
		self.showRegisterModal = function(){
			return $.Deferred(function(defer){
				if(self.user.logged())
				{
					defer.resolve();
				}
				else
				{
					self.registerModal().show()
						.done(function(){
							// User registerred successfully
							defer.resolve();
						})
						.fail(function(){
							// Modal closed. Do we care? Reject, I guess.
							defer.reject();
						});
				}
			});
		};
		
		self.scrollTo = function($element){
			$('body, html').animate({
				scrollTop:$element.offset().top
			});
		};
		
		self.say = function(data, type)
		{
			if(typeof data == "object")
			{
				var message = data.note || data.message;
				var noteclass = data.type ? 'growl-'+data.type : '';
				$.jGrowl(message, {
					position:'bottom-right',
					theme:noteclass,
					header:data.header,
					life:this.calculateGrowlDuration(message),
				});
			}
			else
			{
				if(!type || type.length == 0)
				{
					type = 'info';
				}
				$.jGrowl(data, {
					position:'bottom-right',
					theme:'growl-'+type,
					life:this.calculateGrowlDuration(data),
				});
			}
		};
		
		self.calculateGrowlDuration = function(message)
		{
			message = String(message);
			var duration = (message.split(' ').length) * 300;
			return 4000+duration;
		};
		
		self.showNotes = function(notes)
		{
			if(notes.length > 0)
			{
				for(var i=0;i<notes.length;i++)
				{
					self.say(notes[i]);
				}
			}
		};
		
	};
	
	return new site();
	
});
