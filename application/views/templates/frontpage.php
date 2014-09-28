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
			<?php if(!user::logged()): ?>
					<li><a href="#">Log in</a></li>
					<li><a href="#">Register</a></li>
			<?php else: ?>
			<!--logged in -->
				<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
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
      <img src="https://raw.githubusercontent.com/thoughtbot/refills/master/source/images/placeholder_logo_2.png
" alt="">
    </div>
    <div class="bullet-content">
      <h2>Write</h2>
      <p>The idea is simple. Write three pages per day about any topic that comes to mind. This is not blogging. This is for you and you alone. Write without thinking. Write for no one but you. A little bit more text right here to fill in the space.</p>
      </div>
  </li>  
  <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-2">
      <img src="https://raw.githubusercontent.com/thoughtbot/refills/master/source/images/placeholder_logo_3.png" alt="">
    </div>
    <div class="bullet-content">
      <h2>Unlock Badges</h2>
      <p>As you write and use the site like normal you'll be rewarded for completing streaks, being a member, and other fun and exciting examples. Focus on the writing but have fun with the badges. Ugly text. More text to fill the gaps!</p>
    </div>
  </li>
  <li class="bullet three-col-bullet">
    <div class="bullet-icon bullet-icon-3">
      <img src="https://raw.githubusercontent.com/thoughtbot/refills/master/source/images/placeholder_logo_4.png" alt="">
    </div>
    <div class="bullet-content">
      <h2>Discover yourself</h2>
      <p>Of course the biggest reward that is an outcome of writing your Morning Pages is  you learn about yourself. You learn what is on your mind, what is bothering you, and what your goals are. There's no better reward than that.</p>
    </div>
  </li> 
</ul>
</div>
</section>

<footer class="footer">
 <div class="container">
  <div class="footer-links">
    <ul>
      <li><h3>Content</h3></li>
      <li><a href="javascript:void(0)">About</a></li>
      <li><a href="javascript:void(0)">Contact</a></li>
      <li><a href="javascript:void(0)">Products</a></li>
    </ul>
    <ul>
      <li><h3>Follow Us</h3></li>
      <li><a href="javascript:void(0)">Facebook</a></li>
      <li><a href="javascript:void(0)">Twitter</a></li>
      <li><a href="javascript:void(0)">YouTube</a></li>
    </ul>
    <ul>
      <li><h3>Legal</h3></li>
      <li><a href="javascript:void(0)">Terms and Conditions</a></li>
      <li><a href="javascript:void(0)">Privacy Policy</a></li>
    </ul>
  </div>

  <hr>

  <p>Disclaimer area lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, nostrum repudiandae saepe.</p>
 </div>
</footer>


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
