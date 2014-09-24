<?php if(user::logged()): ?>
		<article>
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
		</article>
	<?php else: ?>
		<article>
			<div id="dummy-content" class="hidden">
				<?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
			</div>
			<div id="page-content"class="container">
				<h2 class="date"><?php echo date('F d, Y'); ?></h2>
			</div>
		</article>
	<?php endif; ?>
	
	<?php if(user::logged()): ?>
		<?php if($page->open()): ?>
			<section>
				<div class="container" id="writing-container">
					<form role="form" action="<?php echo URL::site('write/'.$page->day); ?>" method="post" id="writeform">
						<textarea name="morningpage" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''"></textarea>
						<button class="btn-good pull-right" data-bind="disable:totalwords()<1,css:{'btn-disabled':totalwords()<1}">Submit</button>
						<p class="subtext pull-left">
						      <span data-bind="text:wordcount()">0</span> / 750
	                    </p>
					</form>
				</div>
			</section>
		<?php endif; ?>
	<?php else: ?>
		<section>
			<div class="container" id="writing-container">
				<form role="form" action="<?php echo URL::site('write/'); ?>" method="post" id="writeform" data-bind="submit:submitPage">
					<textarea name="morningpage" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''"></textarea>
					<button class="btn-good pull-right" data-bind="disable:totalwords()<1,css:{'btn-disabled':totalwords()<1}">Submit</button>
					<p class="subtext pull-left">
					      <span data-bind="text:wordcount()">0</span> / 750
                    </p>
				</form>
			</div>
		</section>
	<?php endif; ?>