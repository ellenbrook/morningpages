define([
	'knockout',
	'jquery',
	'bindings',
	'site',
	'models/popnotes'
], function(ko, $, bindings, site, popnotes){
	
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
	
	var tabsModel = function(elem){
		var self = this;
		$(elem).children('li').first().children('a').addClass('is-active').next().addClass('is-open').show();
		$(elem).on('click', 'li > a', function(event){
			if (!$(this).hasClass('is-active'))
			{
				event.preventDefault();
				var accordionTabs = $(this).closest('.accordion-tabs');
				accordionTabs.find('.is-open').removeClass('is-open').hide();
			
				$(this).next().toggleClass('is-open').toggle();
				accordionTabs.find('.is-active').removeClass('is-active');
				$(this).addClass('is-active');
			}
			else
			{
				event.preventDefault();
			}
		});
	};
	$('.accordion-tabs').each(function(){
		ko.applyBindings(new tabsModel(this), this);
	});
	
});
