<h2>Log in</h2>
<?php
if($error)
{
	echo '<div class="alert alert-danger">';
	echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
	echo '<h4>Error</h4>';
	echo 'Wrong username or password. Please try again.';
	echo '</div>';
}
?>
<form action="<?php echo user::url('login'); ?>" method="post" role="form">
	<div class="login">
		<div class="form-group">
			<label for="email">E-mail or username:</label>
			<input type="text" class="form-control" name="email" placeholder="E-mail or username..." id="email" value="<?php echo arr::get($_POST, 'email', ''); ?>" />
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" class="form-control" name="password" id="password" placeholder="Password..." />
		</div>
		<div class="form-group">
			<label>
				<input type="checkbox" name="remember" value="yes" /> Keep me logged in?
			</label>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Log in</button> - <a href="<?php echo user::url('signup'); ?>" title="Sign up">New user</a>?
		</div>
		<a href="<?php echo user::url('help'); ?>" title="Reset your password">forgot password?</a>
	</div>
</form>
