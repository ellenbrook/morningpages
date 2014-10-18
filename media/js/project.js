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
			        
			        self.hamburgerClick = function(element, event){
			            $('#hidden-nav-trigger').toggleClass('navigation-trigger-clicked');
			            $('#user-options-triangle').show();
			            $( "#user-options" ).slideToggle( "slow", function() {
			                if(!$('#user-options').is(':visible'))
			                {
			                    $('#user-options-triangle').hide();
			                }
			            });
			        };
			        
					self.showLoginModal = function(){
						site.showLoginModal();
					};
					
					self.showRegisterModal = function(){
						site.showRegisterModal();
					};
			        
			    };
			
			    ko.applyBindings(new headerModel(), $('#header')[0]);
			    
			    var useroptionsModel = function(){
			        var self = this;
			        
			        self.goToPreviousPage = function(obj, ev){
			            var date = $(ev.target).val();
			            if($(ev.target).val() != 0)
			            {
			                window.location.href = '/write/'+date;
			            }
			        };
			        
					self.tipsModal = new modal( $('#tips-and-tricks') );
					ko.applyBindings(self.tipsModal, $('#tips-and-tricks')[0]);
			
					self.showTipsAndTricks = function(elem, event){
						self.tipsModal.show();
					};
					
				};
				ko.applyBindings(new useroptionsModel(), $('#user-options')[0]);
				
				
				defer.resolve();
    			
    		});
    		
    	};
    	
    };
    return new projectModel();
    
});