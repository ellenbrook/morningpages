
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

<?php

echo $talk->pagination($currentpage);

?>

<ul class="comments">
	<?php if((int)arr::get($_GET, 'page',0)<=1): ?>
		<li class="comment op" id="comment-<?php echo $talk->opid(); ?>" data-id="<?php echo $talk->opid(); ?>">
			<div class="comment-bio">
				<?php if(!$talk->deleted()): ?>
					<?php echo HTML::image($talk->user->gravatar(120)); ?>
					<p class="comment-author">
						<?php echo $talk->user->username(true); ?>
						<div class="post-count">Posts: <?php echo $talk->user->talkreplies->count_all(); ?></div>
					</p>
					<p>
						<?php echo $talk->user->points(); ?> point<?php echo ($talk->user->points()==1?'':'s'); ?>
					</p>
					<p class="comment-detail">
						<?php echo Date::fuzzy_span($talk->created); ?>
					</p>
					<div class="comment-meta">
						<div class="votes">
							<?php echo $talk->votes(); ?> liked
						</div>
					</div>
				<?php else: ?>
					[deleted]
				<?php endif; ?>
			</div>
			<div class="comment-content completearea">
				<?php if(!$talk->deleted()): ?>
					<h3><?php echo $talk->title; ?></h3>
					<?php echo $talk->content(); ?>
				<?php else: ?>
					[deleted]
				<?php endif; ?>
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
				<?php if(user::can_edit($talk->getop()) && !$talk->deleted()): ?>
					<div class="comment-actions">
						<?php if(user::get()->id == $talk->user_id): ?>
							<button class="deletebutton" data-bind="showModal:{element:'#delete-post-modal',done:deletepost}" title="Delete post">
								<span class="fa fa-trash"></span>
							</button>
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
	<?php endif; ?>
<?php
	if((bool)$replies->count())
	{
		foreach($replies as $reply)
		{
?>
			<li class="comment" id="comment-<?php echo $reply->id; ?>" data-id="<?php echo $reply->id; ?>">
				<div class="comment-bio">
					<?php if(!$reply->deleted()): ?>
						<?php echo HTML::image($reply->user->gravatar(100)); ?>
						<p class="comment-author">
							<?php echo $reply->user->username(true); ?>
						</p>
						<p>
							<?php echo $reply->user->points(); ?> point<?php echo ($talk->user->points()==1?'':'s'); ?>
						</p>
						<p class="comment-detail">
							<?php echo Date::fuzzy_span($reply->created); ?>
						</p>
						<div class="comment-meta">
							<div class="votes"><?php echo $reply->votes(); ?> liked</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="comment-content completearea">
					<?php if(!$reply->deleted()): ?>
						<p class="in-reply-to">
<?php
							if($reply->replyto_id != 0)
							{
								echo '<a href="#comment-'.$reply->replyto_id.'" data-bind="click:showParent" title="'.$reply->replyto->excerpt().'">In reply to '.$reply->replyto->user->username().'</a>';
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
				<?php else: ?>
					[deleted]
				<?php endif; ?>
				<div class="comment-footer">
					<?php if(user::can_edit($reply) && !$reply->deleted()): ?>
						<div class="comment-actions">
							<?php if(user::get()->id == $reply->user_id): ?>
								<button class="deletebutton" data-bind="click:deletepost" title="Delete post">
									<span class="fa fa-trash"></span>
								</button>
								<button data-bind="click:edit" title="Edit post">
									<span class="fa fa-pencil"></span>
								</button>
							<?php endif; ?>
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

echo $talk->pagination($currentpage);

?>

<div class="talk-action" id="replyform" data-bind="visible:site.user.logged">
	<form action="<?php echo URL::site($talk->url()); ?>" method="post">
		<input type="hidden" name="replyto_id" data-bind="value:replyto_id()" />
		<fieldset>
			<div class="action">
				<h3>Submit new reply</h3>
			</div>
			
			<p data-bind="visible:replyto_id()>0">
				<a href="#" class="error" data-bind="click:cancelreply">
					<span class="fa fa-remove"></span>
				</a>
				You're replying to <span id="reply-to-author" data-bind="text:replyto()"></span>
			</p>
			<p>
				<label for="new-reply-content">Post a reply</label>
				<textarea name="content" data-bind="autogrow:true, markdownpreview:'#markdown-preview'" placeholder="Type your reply here..." id="new-reply-content"></textarea>
			</p>
			<p class="text-right">
				<button class="button">Submit reply</button>
			</p>
			
			<div class="talk-subscription">
	
				<div class="form-group">
					<label class="stay" for="talk-subscribe">
<?php
						$sub = false;
						if(user::logged())
						{
							$sub = user::get()->talksubscriptions
								->where('talk_id','=',$talk->id)
								->find();
						}
?>
						<input type="checkbox" data-bind="event:{change:subscribe}" value="<?php echo $talk->id; ?>" id="talk-subscribe"<?php echo ($sub && $sub->loaded()?' checked="checked"':''); ?> />
						Subscribe to this topic by e-mail?
					</label>
					
				</div>
				
			</div>
			
			<hr />
			
			<h3>Preview:</h3>
			<div id="markdown-preview"></div>
		</fieldset>
	</form>
</div>
	
<?php echo View::factory('modals/delete-post'); ?>

