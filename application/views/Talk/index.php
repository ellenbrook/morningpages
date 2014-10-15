<div class="page-header">
	<h2>Talk<?php echo ($tag?' <span class="seriffed">about</span> '.$tag->title:''); ?></h2>
</div>

<ul class="tag-nav">
	<li class="tag<?php echo (!$tag||$tag=='all'?' active':''); ?>">
		<?php echo HTML::anchor('talk', 'All'); ?>
	</li>
<?php
	if((bool)$tags->count())
	{
		foreach($tags as $t)
		{
			echo '<li class="tag'.(($tag&&$t->id==$tag->id)?' active':'').'">';
			echo HTML::anchor($t->url(),$t->title);
			echo '</li>';
		}
	}
?>
</ul>

<div class="cards">
<?php
	if((bool)$talks->count())
	{
		foreach($talks as $talk)
		{
			$replies = $talk->replies->where('op','!=',1)->count_all();
			$views = $talk->views;
?>
			<div class="card">
				<?php if($talk->hot()): ?>
					<div class="ribbon-wrapper"><div class="ribbon">Popular</div></div>
				<?php endif; ?>
				<div class="card-icon">
					<?php echo HTML::image($talk->user->gravatar('100')); ?>
					<span><?php echo $talk->username(); ?></span>
				</div>
				<div class="card-content">
					<div class="card-header">
						<a href="<?php echo URL::site($talk->url()); ?>"><?php echo $talk->title; ?></a>
					</div>
					<div class="card-copy">
						<?php echo $talk->excerpt(); ?>
					</div> 
					<div class="card-stats">
						<ul>
							<li><?php echo $views; ?><span>View<?php echo ($views==1?'':'s'); ?></span></li>
							<li><?php echo $replies; ?><span><?php echo ($replies==1?'Reply':'Replies'); ?></span></li>
						</ul>
					</div>
				</div>
			</div>
<?php
		}
	}
	else
	{
?>
		<div class="intro-text">
			<h3><?php echo silly::ruhroh(); ?></h3>
			<h2>There's nothing here yet.</h2>
			<h4>Be the first to start a conversation!</h4>
			<p>Please.. We're lonely!</p>
		</div>
<?php
	}
?>
</div> <!-- end cards -->


<?php

if($numpages > 1)
{
	echo '<div class="pagination">';
	echo '<ul>';
	$url = 'talk/';
	if($tag)
	{
		$url = $tag->url();
	}
	if($currentpage > 1)
	{
		$prevnum = $currentpage - 1;
		$prevurl = $url;
		if($prevnum > 1)
		{
			$prevurl = $url.'?page='.$prevnum;
		}
		echo '<li class="page-prev"><a href="'.(URL::site($prevurl)).'">Prev</a></li>';
	}
	echo '<li>';
	echo '<ul>';
	for($i=1;$i<=$numpages;$i++)
	{
		$iurl = $url.'?page='.$i;
		if($i==1)
		{
			$iurl = $url;
		}
		echo '<li class="'.($currentpage==$i?'active':'').'"><a href="'.URL::site($iurl).'">'.$i.'</a></li>';
	}
	if($currentpage < $numpages)
	{
		echo '<li class="page-next"><a href="'.URL::site($url.'?page='.($currentpage+1)).'">Next</a></li>';
	}
	echo '</ul>';
	echo '</li>';
	echo '</ul>';
	echo '</div>';
}

?>


<?php if(user::logged()): ?>
	<div class="talk-action">
		<form data-bind="validateForm:{rules:{'#new-talk-tag':{min:1,messages:{min:'Please select a topic'}}}}" action="<?php echo URL::site('talk'); ?>" method="post">
			<fieldset>
				<div class="header">
					<h3>Start a new conversation</h3>
				</div>
				<p>
					<label for="new-talk-tag">Topic</label>
					<select name="talktag_id" id="new-talk-tag" class="<?php echo (($errors&&arr::get($errors,'talktag_id',false))?'error':''); ?>">
						<option value="0"<?php echo (!$tag?' selected="selected"':''); ?>>Select</option>
<?php
						if((bool)$tags->count())
						{
							foreach($tags as $t)
							{
								echo '<option value="'.$t->id.'"'.(($tag&&$tag->id==$t->id)?' selected="selected"':'').'>'.$t->title.'</option>';
							}
						}
?>
					</select>
<?php
					if($errors&&arr::get($errors,'talktag_id',false))
					{
						echo '<label for="new-talk-tag" class="error">';
						$errs = arr::get($errors,'talktag_id');
						if(is_array($errs))
						{
							echo '<ul>';
							foreach($errs as $err)
							{
								echo '<li>'.$err.'</li>';
							}
							echo '</ul>';
						}
						else
						{
							echo $errs;
						}
						echo '</label>';
					}
?>
				</p>
				<p>
					<label for="new-talk-title">Title</label>
					<input class="<?php echo (($errors&&arr::get($errors,'title',false))?'error':''); ?>" value="<?php echo arr::get($_POST, 'title',''); ?>" required type="text" id="new-talk-title" name="title" placeholder="Title of your conversation" />
<?php
					if($errors&&arr::get($errors,'title',false))
					{
						echo '<label for="new-talk-title" class="error">';
						$errs = arr::get($errors,'title');
						if(is_array($errs))
						{
							echo '<ul>';
							foreach($errs as $err)
							{
								echo '<li>'.$err.'</li>';
							}
							echo '</ul>';
						}
						else
						{
							echo $errs;
						}
						echo '</label>';
					}
?>
				</p>
				<p>
					<label for="new-talk-content">Content</label>
					<textarea class="<?php echo (($errors&&arr::get($errors,'content',false))?'error':''); ?>" required data-bind="autogrow:true" name="content" placeholder="Your content here..." id="new-talk-content"><?php echo arr::get($_POST, 'content',''); ?></textarea>
<?php
					if($errors&&arr::get($errors,'content',false))
					{
						echo '<label for="new-talk-content" class="error">';
						$errs = arr::get($errors,'content');
						if(is_array($errs))
						{
							echo '<ul>';
							foreach($errs as $err)
							{
								echo '<li>'.$err.'</li>';
							}
							echo '</ul>';
						}
						else
						{
							echo $errs;
						}
						echo '</label>';
					}
?>
				</p>
				<p class="text-right">
					<button class="button">Submit talk</button>
				</p>
			</fieldset>
		</form>
	</div>
<?php endif; ?>
