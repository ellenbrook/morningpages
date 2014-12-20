require.config({
	'paths':{
		'jquery':'vendor/jquery',
		'sammy':'vendor/sammy',
		'jgrowl':'../libs/jgrowl/jquery.jgrowl',
		'bootstrap':'vendor/bootstrap.min',
		'tinymce':'../libs/tiny_mce/jquery.tinymce',
		'canvas-to-blob':'../libs/blueimp-canvas-to-blob/js/canvas-to-blob',
		'load-image':'../libs/blueimp-load-image/js/load-image',
		'jquery.ui.widget':'../libs/jquery-file-upload/js/vendor/jquery.ui.widget',
		'jquery.fileupload':'../libs/jquery-file-upload/js/jquery.fileupload',
		'knockout':'vendor/knockout',
		'typeahead':'../libs/typeahead.js/dist/typeahead.bundle.min',
		'jqueryui':'vendor/jquery-ui-1.10.4.custom.min',
		'nestedsortable':'vendor/jquery.nestedsortable',
		'datepicker':'vendor/bootstrap-datepicker'
		//'gridster':'../libs/gridster/dist/jquery.gridster.min',
		//'async':'../libs/requirejs-plugins/src/async',
		//'propertyParser':'../libs/requirejs-plugins/src/propertyParser',
		//'goog':'../libs/requirejs-plugins/src/goog',
	},
	'shim':{
		'bootstrap':{
			deps:['jquery'],
			exports:"$.fn.popover"
		},
		'jgrowl':{
			deps:['jquery']
		},
		'tinymce':{
			deps:['jquery']
		},
		'nestedsortable':{
			deps:['jquery', 'jqueryui']
			
		}
	}
});
require([
	'knockout',
	'jquery',
	'sammy',
	'bootstrap',
	'site',
	'bindings',
	'viewModels/Files/filebrowser',
	'viewModels/projectModel'
], function(ko, $, sammy, bootstrap, site, bindings, filebrowser, projectModel){
	
	var project = function(){
		var self = this;
		
		site.filebrowser(new filebrowser());
		self.tester = ko.observable('test');
		self.module = ko.observable(false);
		
		ko.applyBindings(site.filebrowser(), $('#filebrowsermodal')[0]);
		ko.applyBindings(site.header, $('#header')[0]);
		
		self.getSiteInfo = function(){
			$.getJSON(site.ajaxurl);
		};
		
		self.getInfo = function(){
			$.getJSON(site.ajaxurl+'site/info', function(reply){
				if(reply.success)
				{
					site.header.info(reply);
				}
			});
		};
		self.getInfo();
		setInterval(self.getInfo, 60000);
		
		self.routing = sammy('body', function(){
			var sam = this;
			sam.loadPage = function(url){
				var me = this;
				site.header.loading(true);
				$.getJSON(url, function(reply){
					site.header.loading(false);
					if(reply.type == 'success')
					{
						var id = '#navitem-'+reply.domain;
						$('#sidebar .nav .active').removeClass('active');
						$(id).addClass('active');
						if(typeof reply.viewModel != 'undefined')
						{
							require([reply.viewModel], function(module){
								ko.cleanNode($('#content-holder')[0]);
								$('#content-holder').html(reply.view);
								self.module(new module(reply));
								ko.applyBindings(self.module(), $('#content-holder')[0]);
							});
						}
						else
						{
							ko.cleanNode($('#content-holder')[0]);
							$('#content-holder').html(reply.view);
							ko.applyBindings(new projectModel(), $('#content-holder')[0]);
						}
						if(reply.styles)
						{
							site.loadcss(reply.styles);
						}
					}
					else if(reply.type == 'redirect')
					{
						window.location.hash = reply.url;
						if(reply.message)
						{
							site.tellUser(reply.message);
						}
					}
					else
					{
						site.tellUser(reply);
					}
				});
			};
			
			// Specific routes
			this.get('#/content/new/:id/:typeid', function(context){
				site.header.loading(true);
				$.get(site.url+'content/new/'+this.params['id']+'/'+this.params['typeid'], function(reply){
					site.header.loading(false);
					if(reply.success)
					{
						window.location.href='#/content/edit/'+reply.id;
					}
					else
					{
						window.location.href='#/content/index/'+this.params['id'];
						site.tellUser(reply);
					}
				}, 'json');
				this.trigger('contentload', 'content');
				return;
			});
			// Catch all routes
			this.get(/.*/, function(context){
				site.unloadcss();
				var hash = window.location.hash.replace('#/', '');
				if(hash.length == 0 || hash == '/' || hash == '#')
				{
					hash = 'controlpanel/index';
				}
				else
				{
					hash = hash;
				}
				sam.loadPage(site.url+hash);
			});
			// POST routing
			this.post(/.*/, function(context){
				var top = this;
				site.header.loading(true);
				$.post(site.ajaxurl+context.path, $(context.target).serialize(), function(reply){
					site.header.loading(false);
					if(reply.success)
					{
						if(reply.redirect)
						{
							sam.loadPage(site.url+reply.redirect);
						}
						else
						{
							sam.loadPage(site.url+context.path);
						}
						$('#content .editable').trigger('clean');
					}
					site.tellUser(reply);
				}, 'json');
			});
			
			this.bind('contentload', function(e,data){
				var id = '#navitem-'+data;
				$('#sidebar .nav .active').removeClass('active');
				$(id).addClass('active');
			});
			
		});
		self.routing.run();
		
	};
	
	new project();
	
});


