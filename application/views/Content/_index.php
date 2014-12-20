<div class="intro">
<?php
	$content = $page->get_block_by_type('content');
	if($content) echo $content;
?>
</div>

<h2 id="senestenyt">Seneste nyts</h2>

<?php

$posts = cms::get_content_by_type('blog', 4, 'created', 'DESC');
if($posts && (bool)$posts->count())
{
	echo '<div class="row">';
	foreach($posts as $post)
	{
?>
		<div class="col-xs-6 col-md-3">
			<div class="article">
				<div class="article-thumb">
					<?php if($post->has_thumb()): ?>
						<?php echo $post->link(array('content'=>'<img src="'.$post->get_thumb(350, 200).'" class="img-responsive" />')) ?>
					<?php endif; ?>
				</div>
				<h3 class="article-title"><?php echo $post->link(); ?></h3>
				<div class="article-excerpt">
<?php
					$excerpt = $post->get_block_by_key('excerpt');
					if($excerpt)
					{
						echo $excerpt;
					}
?>
				</div>
			</div>
		</div>
<?php
	}
	echo '</div>';
}
?>

<h2 id="kunder">Kunder</h2>
<?php

$clients = cms::get_content_by_type('clients', 8, 'title', 'ASC');
if($clients && (bool)$clients->count())
{
	echo '<div class="row">';
	foreach($clients as $client)
	{
		echo '<div class="col-xs-6 col-md-3">';
		echo '<div class="client">';
		if($client->has_thumb())
		{
			echo $client->link(array('content'=>'<img src="'.$client->get_thumb(250, 150).'" class="img-responsive" />'));
		}
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
}

?>

<h2 id="ydelser">Ydelser</h2>

<h2 id="kontakt">Kontakt</h2>
