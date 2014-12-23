define([
	'jquery',
	'knockout',
	'charts'
], function($, ko){
	
	var pagestatsModel = function(){
		var self = this;
		
		self.id = $('#rid').data('id');
		
		$.post('/ajax/pages/rid', {id:self.id}, function(reply){
			$('#rid').find('.loader').hide();
			if(reply.success)
			{
				var ctx = $("#myChart").get(0).getContext("2d");
				var doughnut = new Chart(ctx).Doughnut(reply.data, {
					responsive : true,
					animation: true,
					barValueSpacing : 5,
					barDatasetSpacing : 1,
					showTooltips:true,
					tooltipFillColor: "rgba(0,0,0,0.8)",
					tooltipTemplate: "<%= label %> - <%= value %>%"
				});
			}
			else
			{
				if(reply.problem == 'data')
				{
					$('#rid').find('.errormessage').html(reply.message);
					$("#myChart").remove();
				}
			}
		},'json');
		
	};
	
	ko.applyBindings(new pagestatsModel, $('.main')[0]);
	
});
