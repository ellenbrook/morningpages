<h1><?php echo $page->title(); ?></h1>

<div id="socialresponse">
<?php
	$blocks = $page->get_blocks();
	if((bool)$blocks->count())
	{
		foreach($blocks as $block)
		{
			echo '<section>';
			switch($block->blocktype->key)
			{
				case 'block2columns':
					
					$bg = $block->get_block_by_key('block2columns.background');
					$style = '';
					if($bg)
					{
						$bgcolor = $bg->get_block_by_key('block2columns.background.color');
						$bgimg = $bg->get_block_by_key('block2columns.background.image');
						if($bgcolor)
						{
							$style .= 'background-color:#'.$bgcolor.';';
						}
						if($bgimg)
						{
							$filedata = $bgimg->get_file();
							$file = arr::get($filedata, 'file');
							$style .= 'background-image:url('.$file->show().');';
						}
					}
					
					$colleft = $block->get_block_by_key('block2columns.blockleft');
					$colright = $block->get_block_by_key('block2columns.blockright');
					
					echo '<div class="row" style="'.$style.'">';
					if($colleft)
					{
						$text = $colleft->get_block_by_key('block2columns.blockleft.text');
						$image = $colleft->get_block_by_key('block2columns.blockleft.image');
						echo '<div class="col-xs-6">';
						if($text)
						{
							echo $text;
						}
						elseif($image)
						{
							$imagedata = $image->get_file();
							$image = arr::get($imagedata, 'file');
							echo '<img src="'.$image->show(520).'" class="img-responsive" />';
						}
						echo '</div>';
					}
					if($colright)
					{
						$text = $colright->get_block_by_key('block2columns.blockright.text');
						$image = $colright->get_block_by_key('block2columns.blockright.image');
						echo '<div class="col-xs-6">';
						if($text)
						{
							echo $text;
						}
						elseif($image)
						{
							$imagedata = $image->get_file();
							$image = arr::get($imagedata, 'file');
							echo '<img src="'.$image->show(520).'" class="img-responsive" />';
						}
						echo '</div>';
					}
					echo '</div>';
					break;
			}
			echo '</section>';
		}
	}
?>
</div>