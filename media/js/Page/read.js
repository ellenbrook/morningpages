$(document).ready(function(){
	
	$('#morningpage').autosize().countwords().on('keyup', function(){
		$('#morningpage').countwords(false);
	});
	
});
