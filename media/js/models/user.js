define([
	'knockout',
	'jquery'
],function(ko, $){
	'use strict';
	var user = function(){
		var self = this;
		
		self.email = ko.observable();
		self.options = {
			reminder:ko.observable(false),
			reminder_hour:ko.observable(8),
			reminder_minute:ko.observable(0),
			reminder_meridiem:ko.observable('am'),
			timezone_id:ko.observable(0),
			theme_id:ko.observable(),
			privacymode:ko.observable(false),
			privacymode_minutes:ko.observable(5),
			hemingwaymode:ko.observable(false),
			public:ko.observable(false),
			rtl:ko.observable(false)
		};
		self.loaded = ko.observable(false);
		
		self.password = ko.observable('');
		self.passconfirm = ko.observable('');
		self.wordcount = ko.observable(0);
		self.logged = ko.observable(false);
		self.privacy = {
			timer:null,
			countdown:0,
			timerWarningGiven:false,
			ticks:self.options.privacymode_minutes() * 60,
			startTimer:function(){
				var privaself = this;
				privaself.die();
				return $.Deferred(function(defer){
					$(window).on('keydown',function(){
						privaself.die();
						defer.reject();
					}).on('mousemove',function(){
						privaself.die();
						defer.reject();
					});
					privaself.doTick(defer);
					privaself.timer = setInterval(function(){
						privaself.doTick(defer);
					}, 1000);
				});
			},
			die:function(){
				clearInterval(this.timer);
				this.timer = null;
				this.timerWarningGiven = false;
				this.countdown = self.options.privacymode_minutes() * 60;
				$(window).off('keydown').off('mousemove');
			},
			doTick:function(defer){
				this.countdown -= 1;
				if(this.countdown <= 60 && !this.timerWarningGiven)
				{
					defer.notify('minutewarning');
					this.timerWarningGiven = true;
				}
				if(this.countdown <= 0)
				{
					this.die();
					defer.resolve();
				}
			}
		};
		
		self.init = function(logged){
			if(logged)
			{
				self.logged(true);
				return self.getInfo();
			}
			/*return $.Deferred(function(defer){
				self.getInfo().then(function(){
					self.logged(true);
					defer.resolve();
				});
			});*/
		};
		
		self.getInfo = function(){
			if(!self.loaded())
			{
				return $.get('/ajax/User/info',function(reply){
					if(reply.success)
					{
						self.email(reply.email);
						self.wordcount(reply.wordcount);
						
						self.options.reminder(Boolean(reply.options.reminder));
						self.options.reminder_hour(reply.options.reminder_hour);
						self.options.reminder_minute(reply.options.reminder_minute);
						self.options.reminder_meridiem(reply.options.reminder_meridiem);
						self.options.timezone_id(reply.options.timezone_id);
						self.options.theme_id(reply.options.theme_id);
						self.options.privacymode(Boolean(reply.options.privacymode));
						self.options.privacymode_minutes(parseInt(reply.options.privacymode_minutes));
						self.options.hemingwaymode(Boolean(reply.options.hemingwaymode));
						self.options.public(Boolean(reply.options.public));
						self.options.rtl(Boolean(reply.options.rtl));
						
						self.logged(true);
						self.loaded(true);
					}
				},'json');
			}
			else
			{
				return $.Deferred(function(defer){
					defer.resolve();
				});
			}
		};
		
		self.save_setting = function(setting){
			return $.post('/ajax/user/savesetting',{
				'setting':setting,
				'value':self.options[setting]()
			});
		};
		
	};
	
	return user;
	
});
