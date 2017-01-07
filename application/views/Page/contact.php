<div id="contact-page">
	<h2>Contact</h2>
	<p>
		We invite everyone to use our <a href="https://github.com/ellenbrook/morningpages" title="Github repo for morningpages.net">Github repo</a> for reporting bugs, asking questions, submitting pull requests etc. Alternatively, contact us through <a href="https://twitter.com/morningpagesnet">Twitter</a> or <a target="_blank" href="https://www.facebook.com/morningpages">Facebook</a>.<br />
		We also have an <?php echo HTML::anchor('/about','about page', array('title'=>'About Morning Pages')); ?> which answers many frequently asked questions.
	</p>
	<p>
		Alternatively, fill out the below form and we'll get back to you as soon as possible.
	</p>
	
	<hr />
	
	<h3>Send us a message</h3>
	<form role="form" data-bind="validateForm:true" action="<?php echo URL::site('contact'); ?>" method="post">
		
		<div class="form-group<?php echo (arr::get($errors, 'name', false)?' has-error':'') ?>">
			<label for="suggestions-name">Your name:</label>
			<input type="text" required="required" class="form-control" name="name" id="suggestions-name" />
		</div>
		
		<div class="form-group<?php echo (arr::get($errors, 'email', false)?' has-error':'') ?>">
			<label for="suggestions-email">Your e-mail:</label>
			<input type="email" required="required" class="form-control" name="email" id="suggestions-email" />
			<span class="help-block">
				<?php echo arr::get($errors, 'email', '') ?>
			</span>
		</div>
		
		<div class="form-group<?php echo (arr::get($errors, 'suggestion', false)?' has-error':'') ?>">
			<label for="suggestions-suggestion">Your message</label>
			<textarea required="required" minlength="5" class="text-border" maxlength="1000" name="suggestion" id="suggestions-suggestion" class="form-control"></textarea>
			<span class="help-block">
				<?php echo arr::get($errors, 'suggestion', '') ?>
			</span>
		</div>
		
		<div class="hidden">
			<input type="text" name="sprot" value="" />
		</div>
		
		<div class="text-right">
			<button class="btn btn-primary"><span class="fa fa-envelope"></span> Send</button>
		</div>
		
	</form>
</div>