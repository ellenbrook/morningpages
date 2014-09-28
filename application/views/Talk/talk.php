
<div class="page-header">
	<h2>Talk</h2>
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

<ul class="comments">
	<li class="comment op">
		<div class="comment-image">
			<?php echo HTML::image($talk->user->gravatar(75)); ?>
		</div>
		<div class="comment-content">
			<h3><?php echo $talk->title; ?></h3>
			<?php echo $talk->content(); ?>
			<p class="comment-detail">
				<?php echo Date::fuzzy_span($talk->created); ?>
			</p>
		</div>
	</li>
<?php
	if((bool)$replies->count())
	{
		foreach($replies as $reply)
		{
?>
			<li class="comment">
				<div class="comment-image">
					<?php echo HTML::image($reply->user->gravatar(75)); ?>
				</div>
				<div class="comment-content">
					<?php echo $reply->content(); ?>
					<p class="comment-detail">
						<?php echo Date::fuzzy_span($reply->created); ?>
					</p>
				</div>
			</li>
<?php
		}
	}
	else
	{
		echo '<li><em>No replies yet..</em></li>';
	}
?>

</ul>

<?php if(user::logged()): ?>
	<div class="talk-action">
		<form action="<?php echo URL::site($talk->url()); ?>" method="post">
			<fieldset>
				<div class="action">
					<h3>Submit new reply</h3>
				</div>
				<p>
					<label for="new-reply-content">Post a reply</label>
					<textarea name="content" placeholder="Type your reply here..." id="new-reply-content"></textarea>
				</p>
				<p class="text-right">
					<button class="button">Submit reply</button>
				</p>
			</fieldset>
		</form>
	</div>
<?php endif; ?>
