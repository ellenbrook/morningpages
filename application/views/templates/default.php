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

<header id="header" class="site-header">
	<div class="container">
		<h1 class="logo pull-left">
			<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
		</h1>
		<nav class="default">
			<ul>
				<li><a href="#about">About</a></li>
				<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
				<li><a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">Talk</a></li>
				<?php if(!user::logged()): ?>
					<li><a href="#" data-bind="click:showLoginModal">Login</a></li>
					<li><a href="#" data-bind="click:showRegisterModal">Register</a></li>
				<?php else: ?>
					<li><a href="<?php echo URL::site('me'); ?>" title="Your personal Morning Pages profile">Me</a></li>
				<?php endif; ?>
				<button id="hidden-nav-trigger" class="navigation-trigger" data-bind="click:hamburgerClick"><span class="fa fa-cog"></span></button>
			</ul>
		</nav>
		<div id="user-options-triangle" class="triangle pull-right"></div>
	</div>
</header>

<section id="user-options" class="hidden-menu">
	<div class="container">
		<ul>
			<?php if(user::logged()): ?>
				<li><a href="<?php echo URL::site('user/options'); ?>">User options</a></li>
				<!-- <li>Current streak: echo user::get()->current_streak </li> -->
				<li>
			    	<select data-bind="event:{change:goToPreviousPage}" id="pastposts">
			        	<option value="0">Previous pages</option>
			        	<option value="/">Today</option>
<?php
    					$pages = user::get()
    						->pages
    						->where('type','=','page')
    						->order_by('created', 'DESC')
    						->find_all();
    					$years = array();
    					if((bool)$pages->count())
    					{
    						foreach($pages as $p)
    						{
    							$stamp = $p->created;
    							$year = date('Y', $stamp);
    							if(!array_key_exists($year, $years))
    							{
    								$years[$year] = array();
    							}
    							$month = date('F',$stamp);
    							if(!array_key_exists($month, $years[$year]))
    							{
    								$years[$year][$month] = array();
    							}
    							$years[$year][$month][] = $p;
    						}
    					}
    					foreach($years as $year => $month)
    					{
    						foreach($month as $monthname => $days)
    						{
    							echo '<optgroup label="'.$monthname.', '.$year.'">';
    							foreach($days as $day)
    							{
    							    $dayname = date('l ',$day->created).' the '.date('jS',$day->created);
                                    if($day->day != site::today_slug())
                                    {
                                        //$dayname = 'Today';
                                        echo '<option value="'.$day->day.'"'.($dayname==$day->day?' selected="selected"':'').'>'.$dayname.'</option>';
                                    }
    								
    							}
    							echo '<optgroup>';
    						}
    					}
?>
               		</select>
				</li>
			
				<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
			<?php else: ?>
				<li><a href="#" data-bind="click:showTipsAndTricks">Tips and Tricks</a></li>
				<li>Log in</li>
			<?php endif; ?>
		</ul>
	</div>
</section>

<section class="main">
	<div class="container">
		<?php echo $view; ?>
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
