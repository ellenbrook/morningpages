<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Help</li>
		</ul>
	</div>
</div>

<h1>Reset your password</h1>

<p>
	Enter the e-mail address you signed up with, and we'll send you an e-mail which will help you reset your password.
</p>

<form action="<?php echo user::url('help'); ?>" method="post" role="form" class="form-search">
	<div class="form-group">
		<label for="email" class="control-label">Your e-mailaddress:</label>
		<input type="text" placeholder="Your e-mail..." name="email" class="form-control" id="email" />
	</div>
	<input type="submit" value="Reset password" class="btn btn-primary" />
</form>
