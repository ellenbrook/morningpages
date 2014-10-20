<div class="modal" id="registerModal">
	<div class="modal-window">
		<div class="modal-inner">
			<div class="modal-close" data-bind="click:hide"></div>
			<h2>Register</h2>
			<p class="intro">Registration is fast and simple!</p>
			<div class="modal-left">	 
			 <form role="form" method="post" action="<?php echo user::url('user/signup'); ?>">
				<div class="form-group">
					<span class="modal-login-icon fa fa-envelope"></span>
					<input type="text" class="form-control modal-login" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="E-mail" />
				</div>
				<div class="form-group">
					<span class="modal-login-icon fa fa-user modal-login"></span>
					<input type="text" class="form-control modal-login" name="username" value="<?php echo arr::get($_POST, 'username',''); ?>" placeholder="Username" />
				</div>
				<div class="form-group">
					<span class="modal-login-icon fa fa-lock"></span>
					<input type="password" class="form-control modal-login" value="" name="password" placeholder="Password" />
				</div>
				<div class="form-group">
					<span class="modal-login-icon fa fa-repeat"></span>
					<input type="password" class="form-control modal-login" value="" name="password_confirm" placeholder="Password Confirm" />
				</div>
				<div class="form-group">
					<button type="submit">Register</button>
				</div>
			</form>
			</div>
			<div class="modal-right">
				<ul class="social-buttons">
					<li>
						<a href="<?php echo URL::site('auth/twitter'); ?>" title="Sign in with Twitter">
							<img src="<?php echo URL::site('media/img/sign-in-with-twitter-gray.png'); ?>" alt="Sign in with Twitter" />
						</a>
					</li>
					<li>
						<a href="<?php echo URL::site('auth/twitter'); ?>" title="Sign in with Twitter">
							<img src="<?php echo URL::site('media/img/sign-in-with-twitter-gray.png'); ?>" alt="Sign in with Twitter" />
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>