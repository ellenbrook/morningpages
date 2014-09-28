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
	if(user::logged())
	{
		$theme = user::get()->theme;
	}
?>
<body class="<?php echo $theme; ?>">
	<header id="header" class="index-header">
		<div class="container">
			<h1 class="logo pull-left">
				<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
			</h1>
			<nav class="frontpage">
				<ul>
					<li><a href="#about">About</a></li>
					<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
			<?php if(!user::logged()): ?>
					
			<?php else: ?>
			<!--not logged in -->
				<li>
					<!-- Log in section -->
					<div class="modal">
					  <label for="modal-1">
					    <a class="btn js-btn">Sign in</a> / <a href="#" class="btn js-btn">Register</a>
					  </label>
					  <input class="modal-state" id="modal-1" type="checkbox" />
					  <div class="modal-window">
					    <div class="modal-inner">
					      <label class="modal-close" for="modal-1"></label>
					      <h2>Sign in</h2>
					      <form role="form" method="post" action="<?php echo user::url('login'); ?>">
							<div class="form-group">
								<input type="text" class="form-control" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="E-mail or username" />
							</div>
							<div class="form-group">
								<input type="password" class="form-control" value="" name="password" placeholder="Password" />
							</div>
							<p class="modal-left">
								<a href="<?php echo user::url('signup'); ?>" title="Sign up">Register</a> - <a href="<?php echo user::url('help') ?>" title="Click here if you need help accessing your account">Forgot your password</a>?
							</p>
							<div class="form-group modal-right">
								<button type="submit">Sign in</button>
							</div>
							</form>
							<p class="modal-social">
							<a href="<?php echo URL::site('auth/twitter'); ?>" title="Sign in with Twitter">
								<img src="<?php echo URL::site('media/img/sign-in-with-twitter-gray.png'); ?>" alt="Sign in with Twitter" />
							</a>
							</p>
					    </div>
					  </div>
					</div>
					<!-- End login section -->


				</li>
			<?php endif; ?>
				</ul>
			</nav>
		</div>
	</header>

<section class="hero">
	<div class="hero-inner">
		<div class="container">
			<div class="hero-copy">
				<h2>Welcome to Morning Pages</h2>
				<p>You never know when you’ll find the time. You can’t predict when the mood will strike. That’s why we’re here.</p>	
			</div>
	    	<button>Begin Writing</button>
	    		<p>(No registration required)</p>
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
      <p>The idea is simple. Write three pages per day about any topic that comes to mind. This is not blogging. This is for you and you alone. Write without thinking. Write for no one but you. A little bit more text right here to fill in the space.</p>
      </div>
  </li> 
   <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-3">
      <p><span class="fa fa-lightbulb-o"></span></p>
    </div>
    <div class="bullet-content">
      <h2>Discover yourself</h2>
      <p>Of course the biggest reward that is an outcome of writing your Morning Pages is  you learn about yourself. You learn what is on your mind, what is bothering you, and what your goals are. There's no better reward than that.</p>
    </div>
  </li>  
  <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-2">
       <p><span class="fa fa-shield"></span></p>
    </div>
    <div class="bullet-content">
      <h2>Have fun</h2>
      <p>As you write and use the site like normal you'll be rewarded for completing streaks, being a member, and other fun and exciting examples. Focus on the writing but have fun with the badges. Ugly text. More text to fill the gaps!</p>
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
