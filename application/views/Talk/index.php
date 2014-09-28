<section>
	<div class="container">
		<div class="page-header">
			<h2>Talk</h2>
		</div>
		
		<ul class="tag-nav">
			<li class="tag active">
				<?php echo HTML::anchor('talk', 'All'); ?>
			</li>
<?php
			if((bool)$tags->count())
			{
				foreach($tags as $tag)
				{
					echo '<li class="tag">';
					echo HTML::anchor($tag->url(),$tag->title);
					echo '</li>';
				}
			}
?>
		</ul>
		
	</div>
</section>