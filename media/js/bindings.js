define([
	'knockout',
	'jquery',
	'autogrow',
	'validate'
], function(ko, $){
	
	ko.bindingHandlers.autogrow = {
	    init: function (element, valueAccessor, allBindingsAccessor) {
	        ko.applyBindingsToNode(element, { value: valueAccessor() });        
	        
	        ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
	            $(element).data('autosize').remove();
	        });
	        
	        $(element).autosize({ append: "\n" });
	        
	        $(element).focus(function () {
	            $(element).trigger('autosize');
	        });
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
			$(element).on('submit', function(){
				if($(element).valid())
				{
					valueAccessor()();
				}
				else
				{
					console.log('not valid');
				}
				return false;
			});
		}
	};
	
});


