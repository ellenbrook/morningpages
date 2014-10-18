define([
    'jquery',
    'knockout'
],function($, ko){
    
    var modalModel = function(element)
    {
        var self = this;
        self.modal = element;
        
        self.promise = false;
        
        self.show = function()
        {
            self.modal.show();
            self.promise = $.Deferred();
            return self.promise;
        };
        
        self.hide = function()
        {
            self.modal.hide();
            self.promise.reject();
        };
        
        self.login = function(){
        	
        	self.modal.hide();
        	self.promise.resolve();
        };
        
    };
    
    return modalModel;
    
});