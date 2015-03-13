define([
	'knockout',
	'jquery',
	'site',
	'models/modal',
	'models/autosave',
	'vendor/md5'
], function(ko, $, site, modal, autosave, md5){
	
	var writeModel = function(){
		var self = this;
		
		self.writtenwords = ko.observable('');
		self.totalwords = ko.observable(0);
		self.wordcount = ko.computed(function(){
			var words = $.trim(self.writtenwords()).split(/[\s,.]+/);
			words = $.grep(words, function(n){return (n);});
			var total = words.length;
			if(self.writtenwords().length == 0) total = 0;
			if(site.user.logged())
			{
				total += parseInt(site.user.wordcount());
			}
			self.totalwords(total);
			return total;
		}, this);
		
		self.autosaver = new autosave($('#morningpage-content'));
		
		self.getAutosave = function(){
			var promise = $.Deferred();
			self.autosaver.get().then(function(reply){
				if(reply.content.length > 0)
				{
					var existing = md5(self.writtenwords());
					if(reply.md5 != existing)
					{
						var old = self.writtenwords();
						var newcontent = reply.content+"\r\r"+old;
						$('#morningpage-content').val(newcontent);
						//$('#morningpage-content').blur().focus();
						site.say('An autosave you had from earlier has been prepended to the text area!');
					}
				}
				promise.resolve();
			});
			return promise;
		};
		
		if(site.user.logged())
		{
			site.user.getInfo().then(function(){
				if(site.user.options.hemingwaymode())
				{
					$('#morningpage-content').on('keydown',function(e){
						if(e.keyCode == 8 || e.keyCode == 46)
						{
							return false;
						}
					});
				}
			});
			self.getAutosave().then(function(){
				self.autosaver.start();
			});
			site.user.keepAlive();
		}
		
		site.user.logged.subscribe(function(logged){
			if(logged)
			{
				self.getAutosave().then(function(){
					self.autosaver.start();
				});
				site.user.keepAlive();
			}
			else
			{
				self.autosaver.stop();
			}
		});
		
		
		self.submitPage = function(){
			if(self.writtenwords().length < 1)
			{
				site.say({
					type:'danger',
					message:'You haven\'t written anything!'
				});
				return false;
			}
			if(!site.user.logged())
			{
				site.say({
					type:'danger',
					message:'You must be logged in to save your page. Please log in (or register) and try again (your content won\'t be lost)'
				});
				return false;
			}
			//throw 'something';
			return true;
		};
		
		$(document).on("keydown", function(e){
			// Trigger dummytext
			if(e.ctrlKey && e.keyCode == 32)
			{
				$('#page-content').toggle();
				$('#writeform').toggle();
				$('#dummy-content').toggle();
				$('#sidebar').toggle();
				$('#header').toggle();
				$('#user-options').hide();
				$('footer').toggle();
			}
			// Force save
			if(e.ctrlKey && e.keyCode == 83)
			{
				self.autosaver.save().fail(function(){
					if(!site.user.logged())
					{
						site.say({
							type:'danger',
							message:'You must be logged in to save your page!'
						});
					}
				}).done(function(){
					site.say('Saved!');
				});
				return false;
			}
		});
		
		var isFullscreen = false;
		self.fullscreen = function(){
			if(isFullscreen)
			{
				$('.container').removeClass('not-relative');
				$('#morningpage-content').removeClass('fullscreen').trigger('autosize');
				$('#fullscreen-toolbar').removeClass('fullscreen-toolbar');
				isFullscreen = false;
			}
			else
			{
				$('.container').addClass('not-relative');
				$('#morningpage-content').trigger('autosize.destroy').addClass('fullscreen').focus();
				$('#fullscreen-toolbar').addClass('fullscreen-toolbar');
				isFullscreen = true;
			}
		};
		
	};
	
	ko.applyBindings(new writeModel(),$('#writing-container')[0]);
	
});

