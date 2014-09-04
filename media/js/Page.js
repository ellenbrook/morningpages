var wordlimit = 750;

$.fn.countwords = function(handlesubmit){
	if($(this).length)
	{
		var numwords = $('#page-content').data('wordcount') + $(this).val().trim().split(/[ ,]+/).length;
		
		if($(this).val() == '')
		{
			numwords = $('#page-content').data('wordcount');
		}
		$('#wordcount').html(numwords+"/750 words");
		if(handlesubmit)
		{
			if(numwords >= wordlimit && $(this).val().trim().length >= 1)
			{
				$('#submit').removeAttr('disabled');
			}
			else
			{
				$('#submit').attr('disabled', 'disabled');
			}
		}
	}
	return $(this);
};
