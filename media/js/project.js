define([
    'knockout',
    'jquery',
    'bindings',
    'site',
    'models/modal'
], function(ko, $, bindings, site, modal){
    
    var projectModel = function(){
    	var self = this;
    	
    	self.init = function(logged, notes){
    		
    		return $.Deferred(function(defer){
    			
    			site.init(logged, notes);
    			
    			var headerModel = function(){
					var self = this;
					
					self.site = site;
					
					self.hamburgerClick = function(element, event){
						$('#hidden-nav-trigger').toggleClass('navigation-trigger-clicked');
						$('#user-options-triangle').show();
						$( "#user-options" ).slideToggle( "normal", function() {
							if(!$('#user-options').is(':visible'))
							{
								$('#user-options-triangle').hide();
							}
						});
					};
			        
					self.doneLoggingIn = function(){
						site.say('You have been logged in. Welcome back!');
						site.user.getInfo();
					};
					self.doneRegistering = function(){
						site.say('You have been signed up. Welcome!');
						site.user.getInfo();
					};
					
					self.showRegisterModal = function(){
						site.showRegisterModal();
					};
			        
			    };
			
			    ko.applyBindings(new headerModel(), $('#header')[0]);
				
				defer.resolve();
    			
    		});
    		
    	};
    	
    };
    return new projectModel();
    
});