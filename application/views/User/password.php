<div class="span9" id="content">

<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Update password</li>
		</ul>
	</div>
</div>

<h1>Update password</h1>

<p>Indtast dit nye password for at ændre det</p>

<form action="<?php echo user::url('password/' . $token->token); ?>" method="post">
	<fieldset>
		<div class="control-group<?php echo (arr::get($errors, 'password', false)?' error':'') ?>">
			<label class="control-label" for="password">Nyt password:</label>
			<div class="controls">
				<input type="password" class="input-xlarge" name="password" id="password" />
				<?php if(arr::get($errors, 'password', false)): ?>
					<p class="help-inline">
						<?php echo arr::get($errors, 'password'); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="control-group<?php echo (arr::get($errors, 'password_confirm', false)?' error':'') ?>">
			<label class="control-label" for="password_confirm">Bekræft nyt password:</label>
			<div class="Controls">
				<input type="password" class="input-xlarge" name="password_confirm" id="password_confirm" />
				<?php if(arr::get($errors, 'password_confirm', false)): ?>
					<p class="help-inline">
						<?php echo arr::get($errors, 'password_confirm'); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		
		<input type="submit" value="Opdater password" class="btn btn-primary" />
		
	</fieldset>
</form>

</div>
<div class="span3" id="sidebar">
	Hai Sidebar
</div>
