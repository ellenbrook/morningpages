<?php
	echo files::filebrowser(array(
		'limit' => $block->blocktype->meta('limit')
	));
?>

<div class="filebrowserwrap">
	<?php /*<button data-bind="filebrowserbtn:addfiles" type="button" id="'.$btnid.'" class="'.$btnclass.'" data-limit="'.$limit.'"'.($more?' '.$more:'').'>
		<span class="'.$icon.'"></span>
	</button> */ ?>
	<ul class="thumbnails row" data-bind="foreach:files,visible:files().length>0">
		<li class="col-xs-2">
			<div class="thumbnail">
				<input type="hidden" data-bind="attr:{'name':'block[<?php echo $block->id; ?>][files]['+id+'][id]'},value:id" value="" data-bind="value:id()" />
				<div class="closer">
					<a href="#" class="close filebrowser-file-deleter" data-bind="click:$root.removeFile" title="Fjern">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
				</div>
				<a href="#" data-bind="css:{fancybox:image()}" rel="gallery">
					<img src="#" data-bind="attr{src:image()?biggerthumb():thumb()}" />
				</a>
				<div class="caption">
					<div class="form-group">
						<?php /* <textarea data-bind="value:description(),attr:{'name':'filedescription[<?php echo $block->id; ?>]['+id+']'}" placeholder="Beskrivelse.." name=""></textarea> */ ?>
						<textarea data-bind="value:description(),attr:{'name':'block[<?php echo $block->id; ?>][files]['+id+'][description]'}" placeholder="Beskrivelse.." name=""></textarea>
					</div>
					<div class="form-group">
						<?php /*<input type="text" class="form-control" data-bind="value:alt(),attr:{'name':'alt[<?php echo $block->id; ?>]['+id+']'}" placeholder="Alt tekst.." /> */ ?>
						<input type="text" value="" class="form-control" data-bind="value:alt(),attr:{'name':'block[<?php echo $block->id; ?>][files]['+id+'][alt]'}" placeholder="Alt tekst.." />
					</div>
				</div>
			</div>
		</li>
	</ul>
</div>

<?php /* <div class="row-fluid">
<?php
	$limit = $block->blocktype->meta('limit');
	if(!$limit)
	{
		$limit = 0;
	}
?>
	/*<div class="file-uploader" data-limit="<?php echo $limit; ?>" id="file-uploader" data-blockid="<?php echo $block->id; ?>">
		<span class="btn fileinput-button gallery-btn" id="gallerybtn">
			<i class="icon-upload"></i>
			<span>Vælg filer</span>
			<input type="file" name="files[]" <?php echo ($limit != 1?'multiple="multiple" ':''); ?>/>
		</span>
	</div>
	<div class="progress hide">
		<div class="progress progress-info progress-striped">
			<div class="bar" style="width:0;"></div>
		</div>
	</div>
	<p class="clearfix"></p>
	<ul class="thumbnails galleryimgs" data-blockid="<?php echo $block->id; ?>">
<?php
		if((bool)$block->files->count_all())
		{
			$view = view::factory('cms/content/blocks/gallery/image');
			foreach($block->files->find_all() as $file)
			{
				$v = $view;
				$v->file = $file;
				echo $v->render();
			}
		}
?>
	</ul>
</div> */ ?>