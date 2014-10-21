define([
	'knockout',
	'jquery',
	'site',
	'autogrow',
	'validate'
], function(ko, $, site){
	
	ko.bindingHandlers.autogrow = {
	    init: function (element, valueAccessor, allBindingsAccessor) {
	        
	        ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
	            $(element).data('autosize').remove();
	        });
	        
	        $(element).autosize({ append: "\n" });
	        
	        $(element).focus(function () {
	            $(element).trigger('autosize');
	        });
	    }
	};
	
	ko.bindingHandlers.ImAburger = {
		init:function(element) {
			$(element).on('click',function(){
				$(this).find('.hamburger-line:nth-child(2)').toggleClass('rotate-left');
				$(this).find('.hamburger-line:nth-child(3)').toggleClass('rotate-right');
				$(this).find('.hamburger-line:nth-child(1)').toggleClass('hide-me');
				$('.mobile-navigation').toggleClass('mobile-navigation-active');
			});
		}
	};
	
	ko.bindingHandlers.fadeVisible = {
		init: function (element, valueAccessor) {
			var value = valueAccessor();
			$(element).toggle(ko.utils.unwrapObservable(value));
		},
		update: function (element, valueAccessor) {
			var value = valueAccessor();
			ko.utils.unwrapObservable(value) ? $(element).slideDown() : $(element).slideUp();
		}
	};

	ko.bindingHandlers.showModal = {
		init:function(element, valueAccessor){
			$(element).on('click',function(){
				$('#'+valueAccessor()).show( "fast" );
				$( ".modal-overlay" ).css({
					"display": "block", 
					"background": "rgba(0,0,0,.25)"
				});
				return false;
			});
		}
	};
	ko.bindingHandlers.hideModal = {
		init:function(element, valueAccessor){
			$(element).on('click',function(){
				$( "#"+valueAccessor()+", .shortcuts-modal, .modal-overlay" ).hide( "fast" );
				$( ".modal-overlay" ).css({
					"display": "none", 
					"background": "rgba(0,0,0,0)"
				});
				return false;
			});
		}
	};
	
	ko.bindingHandlers.validateForm = {
		init:function(element, valueAccessor){
			
			var arg = valueAccessor();
			var argtype = typeof valueAccessor();
			
			$(element).validate({
				invalidHandler:function(){
					if(argtype == 'object')
					{
						if(typeof arg.failnote == 'string')
						{
							site.say({type:'error','note':arg.failnote});
						}
						if(typeof arg.fail == 'function')
						{
							return arg.fail();
						}
					}
					return false;
				}
			});
			
			
			if(argtype == 'object')
			{
				if(arg.rules && typeof arg.rules == 'object')
				{
					for(elem in arg.rules)
					{
						$(elem).rules('add', arg.rules[elem]);
					}
				}
			}
			
			$(element).on('submit',function(){
				
				if($(element).valid())
				{
					if(argtype == 'function')
					{
						return arg($(element).serialize());
					}
					else
					{
						if(argtype == 'object')
						{
							if(typeof arg.successnote == 'string')
							{
								site.say({type:'success','note':arg.successnote});
							}
							if(typeof arg.success == 'function')
							{
								return arg.success($(element).serialize());
							}
						}
					}
					console.log('');
					return true;
				}
				
			});
			
		}
	};
	
});


