define([
    'jquery',
    'knockout'
],function($, ko){
    
    var modalModel = function(element)
    {
        var self = this;
        self.modal = element;
        
        self.show = function()
        {
            self.modal.show();
        };
        
        self.hide = function()
        {
            self.modal.hide();
        };
    };
    
    return modalModel;
    
});