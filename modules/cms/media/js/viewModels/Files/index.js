define([
	'jquery',
	'knockout',
	'site',
	'models/Files/file'
], function($, ko, site, file){
	
	function page(num, current)
	{
		var self = this;
		
		self.number = num;
		self.current = ko.observable(current);
		self.clicked = function(page, what, the){
			console.log(what, the);
			console.log(self.number);
			return false;
		};
	}
	
	return function(){
		var self = this;
		
		self.currentpage = ko.observable({number:1});
		self.search = ko.observable(false);
		self.limit = ko.observable(5);
		self.totalresults = ko.observable(0);
		self.files = ko.observableArray();
		self.pages = ko.observableArray();
		
		self.addfiles = function(files) {
			console.log(files);
		};
		
		self.switchPage = function(page) {
			console.log(self.currentpage(), page.number);
			if(self.currentpage().number != page.number)
			{
				self.currentpage(page);
				self.getCurrentPage();
			}
			return false;
		};
		
		self.getCurrentPage = function() {
			$.post(site.ajaxurl+'files/get', {'page':self.currentpage().number,limit:self.limit()}, function(reply){
				if(reply.success)
				{
					self.pages.removeAll();
					for(var i=0;i<reply.total;i++)
					{
						var p = new page(i+1,(i==self.currentpage().number-1));
						self.pages.push(p);
						if(i == self.currentpage().number-1)
						{
							self.currentpage(page);
						}
					}
					self.files.removeAll();
					for(var i=0;i<reply.files.length;i++)
					{
						self.files.push(new file(reply.files[i]));
					}
				}
				else
				{
					site.tellUser(reply);
				}
			}, 'json');
		};
		
		self.getCurrentPage();
		
		/*$.getJSON(site.ajaxurl+'files/get_filebrowser_files', {}, function(reply){
			if(reply.success)
			{
				for(var i=0;i<reply.files.length;i++)
				{
					self.files.push(new file(reply.files[i]));
				}
			}
		});*/
		
	};
	
});
