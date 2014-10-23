<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Suggestions</li>
		</ul>
	</div>
</div>


<h1>Suggestions</h1>
<p>
	Have an idea to make Morning Pages better? Let us know.
</p>

<form role="form" action="<?php echo URL::site('suggestions'); ?>" method="post">
	
	<div class="form-group<?php echo (arr::get($errors, 'name', false)?' has-error':'') ?>">
		<label for="suggestions-name">Your name:</label>
		<input type="text" class="form-control" name="name" id="suggestions-name" />
	</div>
	
	<div class="form-group<?php echo (arr::get($errors, 'email', false)?' has-error':'') ?>">
		<label for="suggestions-email">Your e-mail:</label>
		<input type="email" class="form-control" name="email" id="suggestions-email" />
		<span class="help-block">
			<?php echo arr::get($errors, 'email', '') ?>
		</span>
	</div>
	
	<div class="form-group<?php echo (arr::get($errors, 'suggestion', false)?' has-error':'') ?>">
		<label for="suggestions-suggestion">Your suggestion</label>
		<textarea name="suggestion" id="suggestions-suggestion" class="form-control"></textarea>
		<span class="help-block">
			<?php echo arr::get($errors, 'suggestion', '') ?>
		</span>
	</div>
	
	<div class="hidden">
		<input type="text" name="sprot" value="" />
	</div>
	
	<button class="btn btn-primary">Send</button>
	
</form>
