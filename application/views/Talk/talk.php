
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
	<li class="comment op" data-id="<?php echo $talk->opid(); ?>">
		<div class="comment-bio">
			<?php echo HTML::image($talk->user->gravatar(120)); ?>
			<p class="comment-author">
				<?php echo $talk->user->username(); ?>
			</p>
			<p class="comment-detail">
				<?php echo Date::fuzzy_span($talk->created); ?>
			</p>
			<div class="comment-meta">
				<div class="votes">
					<?php echo $talk->votes(); ?> liked
				</div>
			</div>
		</div>
		<div class="comment-content completearea">
			<h3><?php echo $talk->title; ?></h3>
			<?php echo $talk->content(); ?>
		</div>
		<div class="comment-content editarea">
			<textarea></textarea>
			<div class="text-right">
				<button data-bind="click:edit" title="Save">
					Save
				</button>
				<a href="#" data-bind="click:canceledit">Cancel</a>
			</div>
		</div>
		<div class="comment-footer">
			<?php if(user::logged()): ?>
				<div class="comment-actions">
					<?php if(user::get()->id == $talk->user_id): ?>
						<button class="editbutton" data-bind="click:edit" title="Edit">
							<span class="fa fa-pencil"></span>
						</button>
					<?php endif; ?>
					<button data-bind="click:comment">Comment</button>
					<button data-bind="click:quote">Reply</button>
					<button class="<?php echo (user::get()->votedon($talk->opid())?'voted':''); ?>" data-bind="click:vote">+1</button>
				</div>
			<?php endif; ?>
		</div>
	</li>
<?php
	if((bool)$replies->count())
	{
		foreach($replies as $reply)
		{
?>
			<li class="comment" data-id="<?php echo $reply->id; ?>">
				<div class="comment-bio">
					<?php echo HTML::image($reply->user->gravatar(100)); ?>
					<p class="comment-author">
						<?php echo $reply->user->username(); ?>
					</p>
					<p class="comment-detail">
						<?php echo Date::fuzzy_span($reply->created); ?>
					</p>
					<div class="comment-meta">
						<div class="votes"><?php echo $reply->votes(); ?> liked</div>
					</div>
				</div>
				<div class="comment-content completearea">
					<p>
<?php
						if($reply->replyto_id != 0)
						{
							echo '<em>In reply to '.$reply->replyto->user->username().'</em>';
						}
?>
					</p>
					<?php echo $reply->content(); ?>
				</div>
				<div class="comment-content editarea">
					<textarea></textarea>
					<div class="text-right">
						<button data-bind="click:edit" title="Save">
							Save
						</button>
						<a href="#" data-bind="click:canceledit">Cancel</a>
					</div>
				</div>
				<div class="comment-footer">
					<?php if(user::logged()): ?>
						<div class="comment-actions">
							<?php if(user::get()->id == $reply->user_id): ?>
								<button data-bind="click:edit" title="Edit">
									<span class="fa fa-pencil"></span>
								</button>
							<?php endif; ?>
							<button data-bind="click:comment">Comment</button>
							<button data-bind="click:quote">Reply</button>
							<button class="<?php echo (user::get()->votedon($reply->id, 'talkreply')?'voted':''); ?>" data-bind="click:vote">+1</button>
						</div>
					<?php endif; ?>
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
	<div class="talk-action" id="replyform">
		<form action="<?php echo URL::site($talk->url()); ?>" method="post">
			<input type="hidden" name="replyto_id" data-bind="value:replyto_id()" />
			<fieldset>
				<div class="action">
					<h3>Submit new reply</h3>
				</div>
				<div class="loader"></div>
				<p data-bind="visible:replyto_id()!=0">
					<a href="#" class="error" data-bind="click:cancelreply">
						<span class="fa fa-remove"></span>
					</a>
					You're replying to <span id="reply-to-author" data-bind="text:replyto()"></span>
				</p>
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
