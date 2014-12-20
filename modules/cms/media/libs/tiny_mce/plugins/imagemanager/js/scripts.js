
$(document).ready(function(){
	
	/**
	 * List of existing files 
	 */
	
	// Tabs
	$('.nav-tabs a').click(function(e){
		e.preventDefault();
		$(this).tab('show');
	});
	
	// Trigger modal
	$('.modaltoggler').live('click',function(){
		var $me = $(this);
		$.post('ajax/getfile.php',{
			fileid:$me.data('fileid')
		}, function(reply){
			if($me.hasClass('img'))
			{
				$('#preview-image').attr('src',reply.preview);
				$('#insert-img-btn').data('fileid',$me.data('fileid'));
				$('#image-width').val(reply.width).data('origwidth',reply.width);
				$('#image-height').val(reply.height).data('origheight',reply.height);
				$('#image-alt').val(reply.alt);
				$('#image-description').val(reply.description);
				$('#imgmodal').modal('show');
			}
			else
			{
				$('#filemodal').modal('show');
			}
		},'json');
		return false;
	});
	
	$('#image-alignment').change(function(){
		$('#alignment-preview-image').removeClass('alignleft alignright');
		if($(this).val() == 'left')
		{
			$('#alignment-preview-image').addClass('alignleft');
		}
		if($(this).val() == 'right')
		{
			$('#alignment-preview-image').addClass('alignright');
		}
	});
	
	// Pagination
	$('.pagination-toggler').click(function(){
		if(!$(this).parent().hasClass('active'))
		{
			$('#searcher').val('');
			$('#loadindicator').show();
			$('#imagestable').hide();
			$('.pagination ul').find('.active').removeClass('active');
			$('.pagination ul').find('li a[data-page="'+$(this).data('page')+'"]').parent().addClass('active');
			$.get('ajax/pagination.php?page='+$(this).data('page'), function(reply){
				$('#imagestablebody').html(reply);
				$('#loadindicator').hide();
				$('#imagestable').show();
			});
		}
		return false;
	});
	
	// Search
	$('#searcher').keyup(function(e){
		var k = e.keyCode;
		var ignore = [16,37,38,39,40,35,36,33,34,17,18];
		if(ignore.indexOf(k))
		{
			$('#loadindicator').show();
			$('#imagestable').hide();
			if($(this).val().length > 0)
			{
				$('#loadindicator').show();
				$('#imagestable').hide();
				var $me = $(this);
				$.post('ajax/search.php', {
					query:$me.val()
				}, function(reply){
					$('#imagestablebody').html(reply);
					$('#loadindicator').hide();
					$('#imagestable').show();
				});
			}
			else
			{
				$('.pagination ul').find('.active').removeClass('active');
				$('.pagination ul').find('li a[data-page="1"]').parent().addClass('active');
				$.get('ajax/pagination.php?page=1', function(reply){
					$('#imagestablebody').html(reply);
					$('#loadindicator').hide();
					$('#imagestable').show();
				});
			}
		}
	});
	
	// Change width/height
	$('#image-width').keyup(function(){
		if($('#proportional-scale').is(':checked'))
		{
			var w = parseInt($(this).data('origwidth'));
			var h = parseInt($('#image-height').data('origheight'));
			var s =  h/w;
			$('#image-height').val(Math.floor($(this).val()*s));
		}
	});
	$('#image-height').keyup(function(){
		if($('#proportional-scale').is(':checked'))
		{
			var h = parseInt($(this).data('origheight'));
			var w = parseInt($('#image-width').data('origwidth'));
			var s =  w/h;
			$('#image-width').val(Math.floor($(this).val()*s));
		}
	});
	$('#proportional-scale').click(function(){
		if($(this).is(':checked'))
		{
			var w = parseInt($('#image-width').data('origwidth'));
			var h = parseInt($('#image-height').data('origheight'));
			var s =  h/w;
			$('#image-height').val(Math.floor($('#image-width').val()*s));
		}
	});
	
	// Insert image button
	$('#insert-img-btn').click(function(){
		var width = $('#image-width').val();
		var height = $('#image-height').val();
		var zefloat = $('#image-alignment').val();
		var alt = $('#image-alttext').val();
		var description = $('#image-description').val();
		var $me = $(this);
		$.post('ajax/getimage.php',{
			'fileid':$me.data('fileid'),
			'width':width,
			'height':height,
			'float':zefloat,
			'alt':alt,
			'description':description
		}, function(reply){
			if(reply.success)
			{
				var ed = tinyMCEPopup.editor;
				ed.execCommand('mceInsertContent', false, reply.file);
				tinyMCEPopup.close();
			}
			else
			{
				alert(reply.message);
			}
		},'json');
		return false;
	});
	
	/**
	 * Insert from URL 
	 */
	
	//http://stackoverflow.com/questions/3646914/how-do-i-check-if-file-exists-in-jquery-or-javascript
	$('#insert-url').change(function(){
		if($(this).val().length > 0)
		{
			
		}
	});
	
});
