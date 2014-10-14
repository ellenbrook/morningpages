
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
	<li class="comment">
		<div class="comment-bio">
			<?php echo HTML::image($talk->user->gravatar(120)); ?>
			<p class="comment-detail">
				<?php echo Date::fuzzy_span($talk->created); ?>
			</p>
		</div>
		<div class="comment-content">
			<h3><?php echo $talk->title; ?></h3>
			<?php echo $talk->content(); ?>
		</div>
		<div class="comment-footer">
			<div class="comment-actions">
				<button>Comment</button>
				<button>Reply</button>
				<button>+1</button>
			</div>
		</div>
	</li>
<?php
	if((bool)$replies->count())
	{
		foreach($replies as $reply)
		{
?>
			<li class="comment">
				<div class="comment-bio">
					<?php echo HTML::image($reply->user->gravatar(100)); ?>
					<p class="comment-detail">
						<?php echo Date::fuzzy_span($reply->created); ?>
					</p>
				</div>
				<div class="comment-content">
					<?php echo $reply->content(); ?>
				</div>
				<div class="comment-footer">
					<div class="comment-actions">
						<button>Comment</button>
						<button>Reply</button>
						<button>+1</button>
					</div>
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

<?php

if($numpages > 1)
{
	echo '<div class="pagination">';
	echo '<ul>';
	$url = $url = $talk->url();
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
