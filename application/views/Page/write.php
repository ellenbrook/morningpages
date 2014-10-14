<?php

echo markdown::instance()->convert('*hello*');

?>

<article>
	<?php if(user::logged()): ?>
	    <div id="dummy-content" class="hidden">
            <?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
        </div>
		<div id="page-content"class="container">
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
						<form role="form" action="<?php echo URL::site('write/'.$page->day); ?>" method="post" id="writeform">
							<textarea id="morningpage-content" name="content" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''"></textarea>
							<button data-bind="disable:totalwords()<1,css:{'btn-disabled':totalwords()<1}" class="writing-submit">Submit</button>
							<p class="subtext">
							      <span data-bind="text:wordcount()">0</span> / 750
		                    </p>
						</form>
			<?php endif; ?>
		<?php else: ?>
					<form role="form" action="<?php echo URL::site('write/'); ?>" method="post" id="writeform" data-bind="submit:submitPage">
						<textarea name="morningpage" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''"></textarea>
						<button data-bind="disable:totalwords()<1,css:{'btn-disabled':totalwords()<1}"  class="writing-submit">Submit</button>
						<p class="subtext">
						      <span data-bind="text:wordcount()">0</span> / 750
		                </p>
					</form>
		<?php endif; ?>
	</div>
</article>