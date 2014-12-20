<li class="file">
	<div class="thumbnail">
		<a href="#" class="filebrowser-thumbnail" data-fileid="<?php echo $file->id; ?>">
			<img src="<?php echo $file->show(NULL,100); ?>" />
			<?php if(!$file->is_image()): ?>
				<span class="nameoverlay"><?php echo $file->filename(); ?></span>
			<?php endif; ?>
		</a>
		<div class="filebrowser-file-checkbox hide">
			<span class="glyphicon glyphicon-ok icon-white"></span>
		</div>
	</div>
</li>
