define([
	'jquery',
	'knockout',
	'tinymce',
	'jgrowl',
	'viewModels/header'
], function($, ko, tinymce, grr, header){
	
	var site = function() {
		var self = this;
		
		self.siteurl = window.location.pathname.split('/')[1]+'/';
		self.url = window.location.pathname+'/';
		self.tinymceurl = self.url+"media/libs/tiny_mce/tiny_mce.js";
		self.ajaxurl = self.url+"ajax/";
		self.loaded_stylesheets = [];
		self.filebrowser = ko.observable();
		self.header = new header(self);
		
		self.scrollToElement = function(element)
		{
			$('body, html').animate({
				scrollTop:$(element).offset().top
			});
		};
		
		self.tellUser = function(data, type)
		{
			$.jGrowl.defaults.closerTemplate = '<div>[Luk alle]</div>';
			if(typeof data == "object")
			{
				var message = data.message;
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
		
		self.loadcss = function(styles){
			for(var i=0;i<styles.length;i++)
			{
				if(typeof this.loaded_stylesheets[styles[i]] == 'undefined')
				{
					var $link = $('<link rel="stylesheet" type="text/css" href="'+styles[i]+'" />');
					$('head').append($link);
					this.loaded_stylesheets.push($link);
				}
			}
		};
		
		self.unloadcss = function(){
			for(var i=0;i<this.loaded_stylesheets.length;i++)
			{
				$(this.loaded_stylesheets[i]).remove();
			}
			delete this.loaded_stylesheets;
			this.loaded_stylesheets = [];
		};
		
		self.redirect = function(to){
			window.location.hash=to;
		};
		
		self.loadtinymce = function($elem) {
			var me = this;
			$elem.tinymce({
				script_url:me.tinymceurl,
				plugins:"imagemanager,pdw,inlinepopups,emotions, fullscreen,preview, paste, media, advlink",
				dialog_type:"modal",
				skin:'ka',
				convert_urls:false,
				theme_advanced_buttons1:"pdw_toggle,|,formatselect,|,bold,italic,underline,strikethrough,|,link,unlink,imagemanager,|,bullist,numlist,|,forecolor,backcolor,|,blockquote,hr",
				theme_advanced_buttons2:"justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,undo,redo,|,pastetext,pasteword,|,code,charmap,|,removeformat,cleanup,fullscreen",
				theme_advanced_buttons3:"",
				theme_advanced_resizing:true,
				pdw_toggle_on:1,
				pdw_toggle_toolbars:"2",
				theme_advanced_toolbar_align:'left',
				theme_advanced_toolbar_location:'top',
				theme_advanced_statusbar_location:'bottom',
				height:300,
				theme_advanced_resizing: true,
				theme_advanced_resizing_use_cookie : false,
				language:'en'
			});
		};
		
		self.loadpage = function(url){
			var me = this;
			var data = $.getJSON(url, function(reply){
				if(reply.success)
				{
					if(reply.scripts)
					{
						require(reply.scripts);
					}
					if(reply.styles)
					{
						site.loadcss(reply.styles);
					}
					var id = '#navitem-'+reply.domain;
					$('#sidebar .nav .active').removeClass('active');
					$(id).addClass('active');
					
					var $view = reply.view;
					$('#content').html($view);
					me.loadtinymce($('#content textarea.tinymce'));
				}
				else
				{
					site.tellUser(reply);
				}
			});
		};
	};
	return new site();
	
});
