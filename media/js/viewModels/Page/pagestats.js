define([
	'jquery',
	'knockout',
	'charts'
], function($, ko){
	
	var pagestatsModel = function(){
		var self = this;
		
		self.id = $('#rid').data('id');
		
		$.post('/ajax/pages/rid', {id:self.id}, function(reply){
			if(reply.success)
			{
				var ctx = $("#myChart").get(0).getContext("2d");
				$('#rid').find('.loader').hide();
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
					$('#rid').html(reply.message);
				}
			}
		},'json');
		
	};
	
	ko.applyBindings(new pagestatsModel, $('.main')[0]);
	
});
