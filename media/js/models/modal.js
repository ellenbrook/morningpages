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
        
        self.register = function(form){
        	
        	$.post('/ajax/user/register',form,function(reply){
        		if(reply.success)
        		{
        			self.modal.hide();
        			self.promise.resolve();
        		}
        		else
        		{
        			self.modal.find('.errors').html(reply.errors).show();
        			self.modal.find('.errmsg').html('<label class="error">'+reply.message+'</label>').show();
        		}
        	}, 'json');
        	return false;
        	
        };
        
        self.fblogin = function(){
        	fb.init().done().fail(function(){
        		fb.login().done(function(reply){
        			var data = {};
        			data.accessToken = reply.authResponse.accessToken;
        			data.signedRequest = reply.authResponse.signedRequest;
        			data.serviceId = reply.authResponse.userID;
        			fb.api('/me').then(function(reply){
        				data.name = reply.name;
        				data.email = reply.email;
        				$.post('/ajax/user/fbsignup',data,function(reply){
        					if(reply.success)
        					{
        						self.modal.hide();
        						self.promise.resolve(reply, true);
        					}
        					else
        					{
        						fb.deleteme();
        						self.modal.hide();
        						self.promise.reject(reply, true);
        					}
        				},'json');
        			});
        		}).fail(function(){
        			console.log('Facebook login cancelled.');
        		});
        	});
        };
        
        self.confirm = function(){
        	self.modal.hide();
        	self.promise.resolve();
        };
        
    };
    
    return modalModel;
    
});