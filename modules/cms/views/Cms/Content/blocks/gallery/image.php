<li class="col-xs-3">
	<div class="thumbnail">
		<a href="<?php echo $file->show(); ?>" class="fancybox">
			<img src="<?php echo $file->show(150,150, true); ?>" />
		</a>
		<div class="control-group">
			<a href="#" class="file-deleter" data-fileid="<?php echo $file->id; ?>" title="Fjern dette billede">X</a>
		</div>
	</div>
</li>
