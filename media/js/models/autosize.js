define([
	'knockout',
	'jquery',
	'autogrow'
],function(ko, $){
	
	var autosizeModel = function (element) {
		
		ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
			$(element).data('autosize').remove();
		});
		
		$(element).autosize({ append: "\n" });
		
		$(element).focus(function () {
			$(element).trigger('autosize');
		});
	};
	
	return autosizeModel;
	
});
