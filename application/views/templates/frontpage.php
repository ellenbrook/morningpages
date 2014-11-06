<!DOCTYPE html>
<html lang="en">
<head>
	<title>Morning Pages - Free online journaling platform</title>
	<?php /* <script src="//use.typekit.net/rod6iku.js"></script>
	<script>try{Typekit.load();}catch(e){}</script> */ ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Morning Pages" name="name">
	<meta content="<?php echo seo::instance()->description(); ?>" name="description">
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

<?php

echo View::factory('templates/header');

?>

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

<section class="news" id="frontpage-news">
	<div class="container">
		<div class="news">
			<h3>Latest news</h3>
<?php
			$news = ORM::factory('Talk')
				->where('deleted','=',0)
				->where('announcement','=',1)
				->limit(3)
				->find_all();
			if((bool)$news->count())
			{
				echo '<ul>';
				foreach($news as $new)
				{
					echo '<li>';
					echo '<h4>'.HTML::anchor($new->url(), $new->title, array('title'=>$new->title)).'</h4>';
					echo '<p>This is an excerpt of the talk. Maybe just about 100 characters or so? Then we can add a ...</p>';
					echo '</li>';
				}
				echo '</ul>';
			}
?>
		</div>
		<div class="talks">
			<h3>Latest talks</h3>
<?php
			$talks = ORM::factory('Talk')
				->where('deleted','=',0)
				->where('announcement','=',0)
				->limit(3)
				->find_all();
			if((bool)$talks->count())
			{
				echo '<ul>';
				foreach($talks as $talk)
				{
					echo '<li>';
					echo '<h4>'.HTML::anchor($talk->url(), $talk->title, array('title'=>$talk->title)).'</h4>';
					echo '<p>This is an excerpt of the talk. Maybe just about 100 characters or so? Then we can add a ...</p>';
					echo '</li>';
				}
				echo '</ul>';
			}
?>
		</div>
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
