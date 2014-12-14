<h2>Update password</h2>

<p>Enter your new password</p>

<form action="<?php echo user::url('password/' . $token->token); ?>" method="post">
	<fieldset>
		<div class="control-group<?php echo (arr::get($errors, 'password', false)?' error':'') ?>">
			<label class="control-label" for="password">New password:</label>
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
			<label class="control-label" for="password_confirm">Confirm new password:</label>
			<div class="Controls">
				<input type="password" class="input-xlarge" name="password_confirm" id="password_confirm" />
				<?php if(arr::get($errors, 'password_confirm', false)): ?>
					<p class="help-inline">
						<?php echo arr::get($errors, 'password_confirm'); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		
		<input type="submit" value="Update password" class="btn btn-primary" />
		
	</fieldset>
</form>
