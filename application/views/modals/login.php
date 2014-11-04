<div class="modal" id="loginModal">
	<div class="modal-window">
		<div class="modal-inner">
			<div class="modal-close" data-bind="click:hide"></div>
			<h2>Sign In</h2>
			<div class="modal-left">	 
				<form data-bind="validateForm:login" role="form" method="post" action="<?php echo user::url('login'); ?>">
					<p class="errmsg hidden"></p>
					<div class="form-group">
						<span class="modal-login-icon fa fa-user"></span>
						<input type="email" required="required" class="form-control modal-login" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="Email or Username" />
					</div>
					<div class="form-group">
						<span class="modal-login-icon fa fa-lock"></span>
						<input type="password" required="required" class="form-control modal-login" value="" name="password" placeholder="Password" />
					</div>
					<div class="form-group">
						<button type="submit">Sign In</button>
					</div>
				</form>
			</div>
			<div class="modal-right">
				<ul class="social-buttons">
					<li>
						<button data-bind="click:fblogin" class="social-button facebook-button">
					      <span><i class="fa fa-facebook-square"></i></span> Login with Facebook
					    </button>
					</li>
					<li>
						<button  data-bind="click:twitterlogin" class="social-button twitter-button">
					      <span><i class="fa fa-twitter-square"></i></span> Login with Twitter
						</button>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>