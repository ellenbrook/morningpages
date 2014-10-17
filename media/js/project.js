define([
    'knockout',
    'jquery',
    'bindings',
    'site',
    'models/modal', // correct path, relative to the js root dir
    'models/popnotes'
], function(ko, $, bindings, site, modal, popnotes){
    
    var headerModel = function(){
        var self = this;
        self.hamburgerClick = function(element, event){
            $('#hidden-nav-trigger').toggleClass('navigation-trigger-clicked');
            $('#user-options-triangle').show();
            $( "#user-options" ).slideToggle( "slow", function() {
                // Animation complete.
                if(!$('#user-options').is(':visible'))
                {
                    $('#user-options-triangle').hide();
                }
            });
        };
    };

    ko.applyBindings(new headerModel(), $('#header')[0]);
    
    var useroptionsModel = function(){
        var self = this;
        
        self.goToPreviousPage = function(obg, ev){
            var date = $(ev.target).val();
            if($(ev.target).val() != 0)
            {
                window.location.href = '/write/'+date;
            }
        };
        
        self.tipsModal = new modal( $('#tips-and-tricks') );

        self.clickShowTipsAndTricks = function(elem, event){
            self.tipsModal.showModal();
        };
    };
    ko.applyBindings(new useroptionsModel(), $('#user-options')[0]);
    
    
});