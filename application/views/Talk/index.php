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
			$replies = $talk->replies->count_all();
			$views = $talk->views;
?>
			<a href="<?php echo URL::site($talk->url()); ?>" class="card">
				<?php if($talk->hot()): ?>
					<div class="ribbon-wrapper"><div class="ribbon">Popular</div></div>
				<?php endif; ?>
				<div class="card-header">
					<?php echo $talk->title; ?>
				</div>
				<div class="card-copy">
					<?php echo $talk->excerpt(); ?>
				</div>
				<div class="card-stats">
					<ul>
						<li>
							<?php echo HTML::image($talk->user->gravatar('18')); ?>
							<span><?php echo $talk->username(); ?></span>
						</li>
						<li><?php echo $replies; ?><span><?php echo ($replies==1?'Reply':'Replies'); ?></span></li>
						<li><?php echo $views; ?><span>View<?php echo ($views==1?'':'s'); ?></span></li>
					</ul>
				</div>
			</a>
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
</div>

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
