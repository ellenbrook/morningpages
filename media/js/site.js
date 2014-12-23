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
		
		self.init = function(userLogged, notes){
			self.showNotes(notes);
			self.user.init(userLogged);
		};
		
		self.getUserInfo = function(){
			self.user.getInfo().done(function(){
				if(self.user.options.privacymode())
				{
					self.startUserPrivacyTimer();
				}
			});
		};
		
		self.doneLoggingIn = function(){
			self.say('You have been logged in. Welcome back!');
			self.user.getInfo();
		};
		self.doneRegistering = function(){
			self.say('You have been signed up. Welcome!');
			self.user.getInfo();
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
