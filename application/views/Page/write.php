<ul class="accordion-tabs">
  <li class="tab-header-and-content">
    <a href="#" class="is-active tab-link">Tab Item</a>
    <div class="tab-content">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt pellentesque lorem, id suscipit dolor rutrum id. Morbi facilisis porta volutpat. Fusce adipiscing, mauris quis congue tincidunt, sapien purus suscipit odio, quis dictum odio tortor in sem. Ut sit amet libero nec orci mattis fringilla. Praesent eu ipsum in sapien tincidunt molestie sed ut magna. Nam accumsan dui at orci rhoncus pharetra tincidunt elit ullamcorper. Sed ac mauris ipsum. Nullam imperdiet sapien id purus pretium id aliquam mi ullamcorper.</p>
    </div>
  </li>
  <li class="tab-header-and-content">
    <a href="#" class="tab-link">Another Tab</a>
    <div class="tab-content">
      <p>Ut laoreet augue et neque pretium non sagittis nibh pulvinar. Etiam ornare tincidunt orci quis ultrices. Pellentesque ac sapien ac purus gravida ullamcorper. Duis rhoncus sodales lacus, vitae adipiscing tellus pharetra sed. Praesent bibendum lacus quis metus condimentum ac accumsan orci vulputate. Aenean fringilla massa vitae metus facilisis congue. Morbi placerat eros ac sapien semper pulvinar. Vestibulum facilisis, ligula a molestie venenatis, metus justo ullamcorper ipsum, congue aliquet dolor tortor eu neque. Sed imperdiet, nibh ut vestibulum tempor, nibh dui volutpat lacus, vel gravida magna justo sit amet quam. Quisque tincidunt ligula at nisl imperdiet sagittis. Morbi rutrum tempor arcu, non ultrices sem semper a. Aliquam quis sem mi.</p>
    </div>
  </li>
  <li class="tab-header-and-content">
    <a href="#" class="tab-link">Third</a>
    <div class="tab-content">
      <p>Donec mattis mauris gravida metus laoreet non rutrum sem viverra. Aenean nibh libero, viverra vel vestibulum in, porttitor ut sapien. Phasellus tempor lorem id justo ornare tincidunt. Nulla faucibus, purus eu placerat fermentum, velit mi iaculis nunc, bibendum tincidunt ipsum justo eu mauris. Nulla facilisi. Vestibulum vel lectus ac purus tempus suscipit nec sit amet eros. Nullam fringilla, enim eu lobortis dapibus, quam magna tincidunt nibh, sit amet imperdiet dolor justo congue turpis.</p>    
    </div>
  </li>
  <li class="tab-header-and-content">
    <a href="#" class="tab-link">Last Item</a>
    <div class="tab-content">
      <p>Sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus dui urna, mollis vel suscipit in, pharetra at ligula. Pellentesque a est vel est fermentum pellentesque sed sit amet dolor. Nunc in dapibus nibh. Aliquam erat volutpat. Phasellus vel dui sed nibh iaculis convallis id sit amet urna. Proin nec tellus quis justo consequat accumsan. Vivamus turpis enim, auctor eget placerat eget, aliquam ut sapien.</p>
    </div>
  </li>
</ul>



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