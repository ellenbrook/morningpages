$(document).ready(function(){
	
	if($('#page-content').text().length == 0)
	{
		$('#morningpage').focus();
	}
	$('#morningpage').autosize().countwords(true).on('keyup', function(){
		$('#morningpage').countwords(true);
	});
	
	var savetimer = setInterval(function(){
		$.post(ajaxurl+'/pages/autosave', {
			'id':$('#page-content').data('id'),
			'content':$('#morningpage').val()
		}, function(reply){
			if(!reply.success)
			{
				// Do something?
				console.log(reply);
			}
		}, 'json');
	}, 10000);
	
	$(document).on("keydown", function(e){
		if(e.ctrlKey && e.keyCode == 32)
		{
			$('#page-content').toggle();
			$('#writeform').toggle();
			$('#dummy-content').toggle();
			$('#sidebar').toggle();
		}
	});
	
	$('#submit').mouseover(function(){
		console.log('hover');
	});
	
});
