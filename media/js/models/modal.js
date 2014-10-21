define([
    'jquery',
    'knockout'
],function($, ko, site){
    
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
        
        self.login = function(form){
        	
        	$.post('/ajax/user/login',form,function(reply){
        		if(reply.success)
        		{
        			self.modal.hide();
        			self.promise.resolve();
        		}
        		else
        		{
        			self.modal.find('.errmsg').html('<label class="error">'+reply.message+'</label>').show();
        		}
        	}, 'json');
        	return false;
        	
        };
        
        self.confirm = function(){
        	self.modal.hide();
        	self.promise.resolve();
        };
        
    };
    
    return modalModel;
    
});