<!DOCTYPE html>
<html lang="en">
<head>
	<title>Morning Pages :: Write</title>
	<?php /* <script src="//use.typekit.net/rod6iku.js"></script>
	<script>try{Typekit.load();}catch(e){}</script> */ ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Morning Pages" name="name">
	<meta content="One hundred percent anonymous, free, minimalist journaling software for people to write their morning pages." name="description">
	<link href="<?php echo URL::site('media/img/favicon.ico'); ?>" rel="shortcut icon" />
	<link rel="apple-touch-icon" href="<?php echo URL::site('media/img/favicon.png'); ?>" />
	<link rel="stylesheet" type="text/css" id="mainstyles" href="<?php echo URL::site('media/css/style.css'); ?>" />
</head>
<?php
	$theme = '';
	if(user::logged() && user::get()->option->theme_id)
	{
		$theme = ORM::factory('Theme',user::get()->option->theme_id)->name;
	}
?>
<body class="<?php echo $theme; ?>">
	<header id="header" class="index-header">
		<div class="container">
			<a href="<?php echo URL::site(); ?>" title="Morning pages">
				<h1 class="logo pull-left">
				Morning Pages
			</h1></a>
			<nav class="frontpage">
				<ul>
					<li><a href="#about">About</a></li>
					<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
					<li><a href="<?php echo URL::site('talk'); ?>" title="Discuss Porning Pages">Talk</a></li>
			<?php if(!user::logged()): ?>
					<!-- Log in -->
					<li>
						<div class="modal">
						  <label for="sign-in">
						    <div class="btn js-btn">Log in</div>
						  </label>
						  <input class="modal-state" id="sign-in" type="checkbox" />
						  <div class="modal-window">
						    <div class="modal-inner">
						      <label class="modal-close" for="sign-in"></label>
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
										<button type="submit">Sign In</button>
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
						</div><!-- Registration -->
					</li>

					<li>
						<!--begin registration -->
						<div class="modal">
						  <label for="registration">
						    <div class="btn js-btn">Register</div>
						  </label>
						  <input class="modal-state" id="registration" type="checkbox" />
						  <div class="modal-window">
						    <div class="modal-inner">
						      <label class="modal-close" for="registration"></label>
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
										<span class="modal-login-icon fa fa-lock"></span>
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
						</div><!-- Registration -->
					</li>
			<?php else: ?>
			<!--not logged in -->
					<li><a href="<?php echo URL::site('me'); ?>" title="Your personal Morning Pages profile">Me</a></li>
					<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
			<?php endif; ?>
				</ul>
			</nav>
			<div class="hamburger-container">
			  <div class="hamburger-line"></div>
			  <div class="hamburger-line"></div>
			  <div class="hamburger-line"></div>
			</div>
		</div>
	</header>

<section class="hero full-height">
	<div class="hero-inner">
			<div class="hero-copy">
				<h2>Welcome to Morning Pages</h2>
				<p>You never know when you’ll find the time. You can’t predict when the mood will strike. That’s why we’re here.</p>	
	    	<p class="cta-p"><a href="<?php echo URL::site('write'); ?>" class="cta-button">Begin Writing</a></p>
	    	<p class="subtext">(No registration required)</p> 
	    	</div>
	</div>
</section>

<section class="about" id="about">
<div class="container">
	<ul class="bullets">
  <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-1">
      <p><span class="fa fa-pencil"></span></p>
    </div>
    <div class="bullet-content">
      <h2>Write</h2>
      <p>The idea is simple. Write three pages of stream of consciousness thought per day. Don't worry, your morning pages are always private so write anything you'd like.</p>
      </div>
  </li> 
   <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-3">
      <p><span class="fa fa-lightbulb-o"></span></p>
    </div>
    <div class="bullet-content">
      <h2>Discover yourself</h2>
      <p>You'll start to notice patterns and common topics. Use your writing as a cue make changes in your life or even as a worry-free way to blow off some steam.</p>
    </div>
  </li>  
  <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-2">
       <p><span class="fa fa-thumbs-up"></span></p>
    </div>
    <div class="bullet-content">
      <h2>Have fun</h2>
      <p>No matter the reason writing your morning pages will help you unlock badges, stats, and even win contests. So grab your cup of coffee and start writing today!</p>
    </div>
  </li>
</ul>
</div>
</section>

<?php echo View::factory('templates/footer'); ?>


	<script src="<?php echo URL::site('media/js/require.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo URL::site('media/js/config.js'); ?>" type="text/javascript"></script>
	<script>
<?php
		$filename = 'media/js/viewModels/'.$controller.'/'.$action.'.js';
		$include_viewmodel = false;
		if(file_exists($filename))
		{
			$include_viewmodel = true;
		}
?>
		require(['project'], function(){
			<?php if($include_viewmodel): ?>
				require(['viewModels/<?php echo $controller.'/'.$action; ?>']);
			<?php endif; ?>
		});
	</script>
</body>
</html>
