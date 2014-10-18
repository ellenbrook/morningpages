define([
    'jquery',
    'knockout'
],function($, ko){
    
    var modalModel = function(element)
    {
        var self = this;
        self.modal = element;
        
        self.showModal = function()
        {
            self.modal.show();
        };
        
        self.hideModal = function()
        {
            self.modal.hide();
        };
    };
    
    return modalModel;
    
});