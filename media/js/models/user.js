define([
	'knockout',
	'jquery',
	'site'
],function(ko, $, site){
	
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
			public:ko.observable(false)
		};
		self.loaded = false;
		
		self.password = ko.observable('');
		self.passconfirm = ko.observable('');
		self.wordcount = ko.observable(0);
		self.logged = ko.observable(false);
		
		self.getInfo = function(){
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
					self.options.privacymode_minutes(reply.options.privacymode_minutes);
					self.options.hemingwaymode(Boolean(reply.options.hemingwaymode));
					self.options.public(Boolean(reply.options.public));
					self.loaded = true;
				}
			},'json');
		};
		
		self.options.theme_id.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savetheme',{id:self.options.theme_id()},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
					else
					{
						site.theme(reply.theme);
					}
				},'json');
			}
		});
		
		self.options.reminder.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'reminder',
					'value':self.options.reminder()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.reminder_hour.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'reminder_hour',
					'value':self.options.reminder_hour()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.reminder_minute.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'reminder_minute',
					'value':self.options.reminder_minute()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.reminder_meridiem.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'reminder_meridiem',
					'value':self.options.reminder_meridiem()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.timezone_id.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'timezone_id',
					'value':self.options.timezone_id()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.privacymode.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'privacymode',
					'value':self.options.privacymode()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.privacymode_minutes.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'privacymode_minutes',
					'value':self.options.privacymode_minutes()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.hemingwaymode.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'hemingwaymode',
					'value':self.options.hemingwaymode()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		self.options.public.subscribe(function(){
			if(self.loaded)
			{
				$.post('/ajax/user/savesetting',{
					'setting':'public',
					'value':self.options.public()
				},function(reply){
					if(!reply.success)
					{
						site.say(reply);
					}
				},'json');
			}
		});
		
	};
	
	return user;
	
});
