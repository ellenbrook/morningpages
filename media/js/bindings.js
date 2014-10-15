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
					console.log('1');
					if(argtype == 'function')
					{
						console.log('2');
						return arg();
					}
					else
					{
						console.log('3');
						if(argtype == 'object')
						{
							console.log('4');
							if(typeof arg.successnote == 'string')
							{
								site.say({type:'success','note':arg.successnote});
							}
							if(typeof arg.success == 'function')
							{
								console.log('5');
								return arg.success();
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


