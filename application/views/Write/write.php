<article>
	<?php if(user::logged()): ?>
	    <div id="dummy-content" style="display:none;" class="hidden">
            <?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
        </div>
		<div id="page-content" class="container<?php echo (user::logged() && (bool)user::get()->option('rtl')?' rtl':''); ?>">
			<h2 class="date"><?php echo date('F d, Y', $page->created); ?></h2>
			
			<?php if($page->type == 'page' && $page->content != ''): ?>
				<figure class="wordcloud pull-right">
					<div class="wordcloud-heading">
						<h3 class="wordcloud-title">Today's Words</h3>
					</div>
					<div class="wordcloud-body">
						<?php echo $page->wordcloud(); ?>
					</div>
				</figure>
			<?php endif; ?>
			
<?php
			if($page->type == 'page')
			{
				echo $page->content();
			}
			if(!$page->open())
			{
				echo '<hr />';
				echo '<div class="wordcount">';
				echo $page->wordcount.' '.Inflector::plural('word', $page->wordcount);
				echo '</div>';
			}
?>
		</div>
	<?php else: ?>
		<div id="dummy-content" class="hidden">
			<?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
		</div>
		<div id="page-content" class="container">
			<h2 class="date"><?php echo date('F d, Y'); ?></h2>
		</div>
	<?php endif; ?>
	
	<div class="container" id="writing-container">
		<?php if(user::logged()): ?>
			<?php if($page->open()): ?>
				<div class="text-right" id="fullscreen-toolbar">
					<a href="#" data-bind="click:fullscreen">
						<span class="fa fa-arrows-alt"></span>
					</a>
				</div>
				<form role="form" action="<?php echo URL::site('write/'.$page->day); ?>" method="post" id="writeform">
					<input type="hidden" name="start" value="<?php echo time(); ?>" />
					<textarea class="<?php echo (user::logged() && (bool)user::get()->option('rtl')?'rtl':''); ?>" name="content" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''" id="morningpage-content"><?php echo arr::get($_POST, 'content', ''); ?></textarea>
					<button class="writing-submit">Submit</button>
					<p class="subtext">
					      <span data-bind="text:wordcount">0</span> / 750
                    </p>
				</form>
			<?php endif; ?>
		<?php else: ?>
			<div class="text-right" id="fullscreen-toolbar">
				<a href="#" data-bind="click:fullscreen">
					<span class="fa fa-arrows-alt"></span>
				</a>
			</div>
			<form role="form" action="<?php echo URL::site('write/'); ?>" method="post" id="writeform" data-bind="submit:submitPage">
				<input type="hidden" name="start" value="<?php echo time(); ?>" />
				<textarea class="<?php echo (user::logged() && (bool)user::get()->option('rtl')?'rtl':''); ?>" name="content" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''" id="morningpage-content"><?php echo arr::get($_POST, 'content', ''); ?></textarea>
				<button  class="writing-submit">Submit</button>
				<p class="subtext">
				      <span data-bind="text:wordcount">0</span> / 750
                </p>
			</form>
		<?php endif; ?>
	</div>
</article>

