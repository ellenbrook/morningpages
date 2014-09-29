define(['jquery'], function($){
	
	$.fn.tabs = function(){
		var self = this;
		$(self).find('li > a.is-active').next().addClass('is-open').show();
		$(self).on('click', 'li > a', function(event){
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
	
	$.fn.verticaltabs = function(){
		var self = this;
		$(this).find(".js-vertical-tab-content").hide();
		$(this).find(".js-vertical-tab-content:first").show();
		
		/* if in tab mode */
		$(this).find(".js-vertical-tab").click(function(event) {
		  event.preventDefault();
		
		  $(self).find(".js-vertical-tab-content").hide();
		  var activeTab = $(this).attr("rel");
		  $("#"+activeTab).show();
		
		  $(self).find(".js-vertical-tab").removeClass("is-active");
		  $(this).addClass("is-active");
		
		  $(self).find(".js-vertical-tab-accordion-heading").removeClass("is-active");
		  $(self).find(".js-vertical-tab-accordion-heading[rel^='"+activeTab+"']").addClass("is-active");
		});
		
		/* if in accordion mode */
		$(this).find(".js-vertical-tab-accordion-heading").click(function(event) {
		  event.preventDefault();
		
		  $(this).find(".js-vertical-tab-content").hide();
		  var accordion_activeTab = $(this).attr("rel");
		  $("#"+accordion_activeTab).show();
		
		  $(self).find(".js-vertical-tab-accordion-heading").removeClass("is-active");
		  $(this).addClass("is-active");
		
		  $(self).find(".js-vertical-tab").removeClass("is-active");
		  $(self).find(".js-vertical-tab[rel^='"+accordion_activeTab+"']").addClass("is-active");
		});

		
	};
	
});
