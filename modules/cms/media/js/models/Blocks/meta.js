define([
	'jquery',
	'knockout',
	'site'
], function($, ko, site){
	
	return function(info){
		var self = this;
		
		self.id = info.id;
		self.blocktype_id = info.blocktype_id;
		self.key = ko.observable(info.key);
		self.values = ko.observable(info.values);
		
		self.open = ko.observable(false);
		self.toggleOpen = function(){
			self.open(!self.open());
		};
		
		self.save = function(){
			$.post(site.ajaxurl+'super/savemeta',{
				id:self.id,
				key:self.key(),
				value:self.values()
			},function(reply){
				site.tellUser(reply);
			},'json');
		};
		
	};
	
});
