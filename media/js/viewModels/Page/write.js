define([
	'knockout',
	'jquery',
	'site',
	'models/modal',
	'models/autosave'
], function(ko, $, site, modal, autosave){
	
	var writeModel = function(){
		var self = this;
		
		self.writtenwords = ko.observable('');
		self.totalwords = ko.observable(0);
		self.wordcount = ko.computed(function(){
			var total = $.trim(self.writtenwords()).split(/\s+/).length;
			if(self.writtenwords().length == 0) total = 0;
			if(site.user.logged())
			{
				total += parseInt(site.user.wordcount());
			}
			self.totalwords(total);
			return total;
		}, this);
		
		self.getAutosave = function(){
			self.autosaver = new autosave($('#morningpage-content'));
			return self.autosaver.get();
		};
		
		if(site.user.logged())
		{
			site.user.getInfo().then(function(){
				self.writtenwords('');
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
			self.getAutosave().then(function(reply){
				self.writtenwords(reply.content);
				$('#morningpage-content').trigger('autosize');
			});
		}
		else
		{
			site.user.logged.subscribe(function(logged){
				if(logged)
				{
					self.getAutosave().then(function(reply){
						if(reply.content.length > 0)
						{
							var old = self.writtenwords();
							var newcontent = reply.content+"\r"+old;
							self.writtenwords(newcontent);
							$('#morningpage-content').trigger('autosize');
							site.say('We prepended an autosave you had from earlier!');
						}
					});
				}
			});
		}
		
		
		
		self.submitPage = function(){
			console.log('submit 1');
			if(!site.user.logged())
			{
				console.log('submit 2');
				site.say('You must be logged in to save your page. Please log in (or register) and try again (your content won\'t be lost)');
				return false;
			}
			console.log('submit 3');
			return true;
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
				$('footer').toggle();
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

