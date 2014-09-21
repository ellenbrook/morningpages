<?php
	mail('saktomail@gmail.com', 'testing mails');
?>

<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Write</li>
		</ul>
	</div>
</div>
<?php $quote = ORM::factory('Quote')->order_by(DB::expr('RAND()'))->limit(1)->find(); ?>
<h1 class="quote">"<?php echo $quote->text; ?>"</h1>
<?php /* <div class="text-right"><?php echo $quote->author(); ?></div> */ ?>
<?php if($errors && arr::get($errors, 'content', false)): ?>
	<div class="alert alert-danger">
		<h3>Oh no!</h3>
		<p>
			<?php echo arr::get($errors, 'content', ''); ?>
		</p>
	</div>
<?php endif; ?>


<?php if($page->content != ''): ?>
	<div class="wordcloud col-xs-5 pull-right">
		<h3>Today's Words</h3>
		<?php echo $page->wordcloud(); ?>
	</div>
<?php endif; ?>

<h2 class="pagedate"><?php echo $page->date(); ?></h2>

<div id="dummy-content">
	<?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
</div>

<div id="page-content" data-wordcount="<?php echo $page->wordcount(); ?>" data-id="<?php echo $page->id; ?>">
	
	<?php echo $page->content(); ?>
	
</div>

<?php if($page->open()): ?>
	
	<form role="form" action="<?php echo URL::site('write/'.$page->day); ?>" method="post" id="writeform">
<?php
		$autosave = $page->get_autosave();
		$cont = '';
		if($autosave && $autosave->textarea_content() != '')
		{
			echo '<p class="autosave">We autosaved the content from your last visit, here it is:</p>';
			$cont = $autosave->textarea_content();
		}
?>
		<textarea name="morningpage" placeholder="Click here to begin writing" id="morningpage"><?php echo $cont; ?></textarea>
		
		<div class="form-group text-right">
			<span id="wordcount"><?php echo $page->wordcount().'/750 words'; ?></span>
			<button class="btn btn-primary" id="submit" disabled>Submit</button>
		</div>
		
	</form>
<?php endif; ?>
