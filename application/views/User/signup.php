<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Signup</li>
		</ul>
	</div>
</div>

<h1>Sign up in 10 seconds</h1>
<p>
	<?php echo html::anchor(user::slug('login'),'&laquo; Already signed up?'); ?>
</p>
<?php if($errors): ?>
	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Whoops</h4>
		<p>
			<strong>Errors</strong> Please check the form for errors and submit it again
		</p>
	</div>
<?php endif; ?>

<form action="<?php echo user::url('signup'); ?>" method="post">
	<div class="form-group<?php echo (arr::get($errors, 'username', false)?' has-error':'') ?>">
		<label for="register-username" class="control-label">Username</label>
		<div class="form-group">
			<input type="text" class="form-control" name="username" value="<?php echo arr::get($_POST, 'username', ''); ?>" id="register-username" />
			<span class="help-block">
				<?php echo arr::get($errors, 'username', '') ?>
			</span>
		</div>
	</div>
	<div class="form-group<?php echo (arr::get($errors, 'email', false)?' has-error':'') ?>">
		<label for="register-email" class="control-label">E-mail</label>
		<div class="form-group">
			<input class="form-control" type="email" name="email" value="<?php echo arr::get($_POST, 'email', ''); ?>" id="register-email" />
			<span class="help-block">
				<?php echo arr::get($errors, 'email',''); ?>
			</span>
		</div>
	</div>
	<div class="form-group<?php echo (arr::path($errors, '_external.password', false)?' has-error':'') ?>">
		<label for="register-password" class="control-label">Password</label>
		<div class="form-group">
			<input title="Min 5, max 42 characters" value="<?php echo arr::get($_POST, 'password_confirm',''); ?>" class="form-control" type="password" name="password" id="register-password" />
			<span class="help-block">
				<?php echo arr::path($errors, '_external.password'); ?>
			</span>
		</div>
	</div>
	<div class="form-group<?php echo (arr::path($errors, '_external.password_confirm', false)?' has-error':'') ?>">
		<label for="password-again" class="control-label">Confirm password</label>
		<div class="form-group">
			<input type="password" class="form-control" value="<?php echo arr::get($_POST, 'password_confirm',''); ?>" name="password_confirm" class="ileft pw" id="password-again" />
			<span class="help-block">
				<?php echo arr::path($errors, '_external.password_confirm'); ?>
			</span>
		</div>
	</div>
	<input type="submit" value="Sign up" class="btn btn-primary btn-large" />
</form>

