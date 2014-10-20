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
		<h1 class="logo pull-left">
			<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
		</h1>
		<nav class="frontpage">
			<ul>
				<li><a href="#about">About</a></li>
				<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
				<li><a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">Talk</a></li>
				<?php if(!user::logged()): ?>
					<li><a href="#" data-bind="click:showLoginModal">Login</a></li>
					<li><a href="#" data-bind="click:showRegisterModal">Register</a></li>
				<?php else: ?>
					<li><a href="<?php echo URL::site('me'); ?>" title="Your personal Morning Pages profile">Me</a></li>
					<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
				<?php endif; ?>
			</ul>
		</nav>
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
	require(['project'], function(project){
		project.init(<?php echo (user::logged()?'true':'false').', '.site::notes(); ?>).then(function(){
			<?php if($include_viewmodel): ?>
				require(['viewModels/<?php echo $controller.'/'.$action; ?>']);
			<?php endif; ?>
		});
	});
</script>
</body>
</html>
