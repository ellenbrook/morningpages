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
	<meta name="google-site-verification" content="GRSH_5xF1zNVylG4dVPeMMzbAd-3x5snMapOlZwfNp8" />
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


<section class="about" id="frontpage-about">
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

<section class="news" id="frontpage-donations">
	<h2>Morningpages is in need of your help</h2>
	<p>
		We're been running this site non-profit for 4 years now. But it's time for us to decide if we should move on, as both time and finances simply no longer justify us keeping it running alone.
	</p>
	<p>
		So we're leaving it up to you, the community, to decide whether Morningpages will continue to exist. The server will be running until July 1st, 2018. Then we'll shut it down, with a fond memory about a great project, and a fantastic userbase.
	</p>
	<p>
		We've set up a Paypal account, where anyone willing and able can donate, if they feel like it. 100% of the donations will, of course, go to the continued running of the site. But please don't feel obligated or pressured.
	</p>
	<p>
		<a href="https://www.paypal.me/morningpages" title="Donate to Morningpages with Paypal">
			<?php echo HTML::image('/media/img/donate-with-paypal.jpg', array("alt"=>"", "width"=>150)); ?>
		</a>
	</p>
	<p>
		We've set up a page where <a href="<?php echo URL::site('donations'); ?>" title="Morningpages donators">donators</a> can (optionally) get their contribution listed, along with a message.
	</p>
	<p>
		Thanks,<br />
		Eric and Daniel
	</p>
</section>

<section class="news" id="frontpage-news">
	<div class="container">
		<div class="news">
<?php
			$news = ORM::factory('Talk')
				->where('deleted','=',0)
				->where('announcement','=',1)
				->limit(3)
				->find_all();
			if((bool)$news->count())
			{
				echo '<ul>';
?>
				<li>
					<h4>Shutting down the forum</h4>
					<p>
						We've decided to shut down the forum, as it was rarely used, and seems to have attracted the attention of a lot of spambots lately.
					</p>
					<p>
						We invite everyone to use our <a href="https://github.com/ellenbrook/morningpages" title="Github repo for morningpages.net">Github repo</a> instead for reporting bugs, asking questions, submitting pull requests etc.
					</p>
				</li>
<?php
				foreach($news as $new)
				{
					echo '<li>';
					echo '<h4>'.$new->title.'</h4>';
					echo '<p>'.$new->excerpt().'</p>';
					echo '</li>';
				}
				echo '</ul>';
			}
?>
			<!-- <h3>Latest talks</h3>
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
					echo '<p>'.$talk->content().'</p>';
					echo '</li>';
				}
				echo '</ul>';
			}
?> -->
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
