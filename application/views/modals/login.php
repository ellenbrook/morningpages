<div class="modal" id="loginModal">
	<div class="modal-window">
		<div class="modal-inner">
			<div class="modal-close" data-bind="click:hide"></div>
			<h2>Sign In</h2>
			<div class="modal-left">	 
			 <form role="form" method="post" action="<?php echo user::url('login'); ?>">
				<div class="form-group">
					<span class="modal-login-icon fa fa-user"></span>
					<input type="text" class="form-control modal-login" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="Email or Username" />
				</div>
				<div class="form-group">
					<span class="modal-login-icon fa fa-lock"></span>
					<input type="password" class="form-control modal-login" value="" name="password" placeholder="Password" />
				</div>
				<div class="form-group">
					<button type="submit" data-bind="click:login">Sign In</button>
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