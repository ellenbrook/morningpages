define([
	'knockout',
	'jquery'
],function(ko, $){
	
	var modal = function(element){
		var self = this;
		
		self.element = $(element);
		
		self.show = function(){
			self.element.show( "fast" );
			$( ".modal-overlay" ).css({
				"display": "block", 
				"background": "rgba(0,0,0,.25)"
			});
		};
		
		self.hide = function(){
			self.element.hide('fast');
			$(".shortcuts-modal, .modal-overlay" ).hide( "fast" );
			$( ".modal-overlay" ).css({
				"display": "none", 
				"background": "rgba(0,0,0,0)"
			});
		};
		
		
	};
	
	return modal;
	
});
