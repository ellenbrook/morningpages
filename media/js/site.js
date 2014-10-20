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
				self.setupLoginModal();
				self.setupRegisterModal();
			}
			else
			{
				self.user.getInfo().done(function(){
					self.startUserPrivacyTimer();
				});
			}
			
		};
		
		self.startUserPrivacyTimer = function(){
			console.log('startUserPrivacyTimer');
			if(self.user.options.privacymode())
			{
				self.user.privacy.startTimer().progress(function(arg){
					if(arg == 'minutewarning')
					{
						self.say('Your privacysettings will log you out in 1 minute if you do not become active');
					}
				}).done(function(){
					console.log('done - log out');
				}).fail(function(){
					console.log('starting over');
					self.startUserPrivacyTimer();
				});
			}
		};
		
		self.setupLoginModal = function(){
			self.loginModal(new modal($('#loginModal')));
			ko.applyBindings(self.loginModal(), $('#loginModal')[0]);
		};
		
		self.setupRegisterModal = function(){
			self.registerModal(new modal($('#registerModal')));
			ko.applyBindings(self.registerModal(), $('#registerModal')[0]);
		};
		
		self.showLoginModal = function(){
			return $.Deferred(function(defer){
				if(self.user.logged())
				{
					defer.resolve();
				}
				else
				{
					self.loginModal().show()
						.done(function(){
							// User logged in successfully
							defer.resolve();
						})
						.fail(function(){
							// Modal closed. Do we care? Reject, I guess.
							defer.reject();
						});
				}
			});
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
