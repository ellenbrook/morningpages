define([
	'knockout',
	'jquery',
	'bindings',
	'site'
], function(ko, $, bindings, site){
	
	var headerModel = function(){
		var self = this;
		self.hamburgerClick = function(){
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
	};
	ko.applyBindings(new useroptionsModel(), $('#user-options')[0]);
	
	
});
