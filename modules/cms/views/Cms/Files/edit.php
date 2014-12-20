<?php
	echo cms::breadcrumbs(array(
		'Filer' => cms::url('files'),
		'Rediger fil' => ''
	));
?>

<div class="page-header">
	<h1>Rediger fil</h1>
</div>

<div class="row-fluid">
	<form action="<?php echo cms::url('files/edit/'.$file->id); ?>" method="post">
		<div class="span2">	
<?php
			if($file->is_image())
			{
				echo '<a href="'.$file->get().'" class="fancybox"><img src="'.$file->get(100,100).'" /></a>';
				echo '<div class="clearfix"></div>';
				//echo '<a href="'.cms::url('files/imgedit/'.$file->id).'" title="Rediger billede"><i class="icon-wrench"></i></a>';
			}
			else
			{
				echo '<img src="'.$file->get_icon().'" />';
			}
?>
			<h4>Størrelse</h4>
<?php
			echo $file->size();
			if($file->is_image())
			{
				echo '<h4>Dimentioner</h4>';
				list($width, $height) = getimagesize('files/'.$file->filename());
				echo '<div>'.$width.' x '.$height.'</div>';
			}
			$blocks = $file->blocks->find_all();
			if((bool)$blocks->count())
			{
				echo '<h4>Vedhæftet til</h4>';
				echo '<ul>';
				$ids = array();
				foreach($blocks as $block)
				{
					if(!in_array($block->id, $ids))
					{
						$ids[] = $block->id;
						echo '<li><a href="'.cms::url('content/edit/'.$block->content_id).'">'.$block->content->title.'</a></li>';
					}
				}
				echo '</ul>';
			}
?>
		</div>
		<div class="span10">
			
			<div class="control-group">
				<label for="file-filename">Filnavn</label>
				<div class="input-append">
					<input name="filename" class="span7" type="text" value="<?php echo $file->filename; ?>" /><span class="add-on">.<?php echo $file->ext; ?></span>
				</div>
			</div>
			
			<div class="control-group">
				<label for="file-title">Titel</label>
				<input type="text" name="alt" class="span8" id="file-title" value="<?php echo $file->alt; ?>" />
			</div>
			
			<div class="control-group">
				<label for="file-description">Beskrivelse</label>
				<textarea name="description" class="span8" id="file-description"><?php echo $file->description; ?></textarea>
			</div>
			
			<input type="submit" class="btn btn-primary" value="Gem fil" />
			
		</div>
	</form>
</div>
