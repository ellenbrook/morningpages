<!DOCTYPE html>
<html lang="en">
<head>
<title>Morning pages</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Morning Pages" name="name">
<meta content="One hundred percent anonymous, free, minimalist journaling software for people to write their morning pages." name="description">
<link href="<?php echo URL::site('media/img/favicon.ico'); ?>" rel="shortcut icon" />
<link rel="shortcut icon" href="<?php echo URL::site('media/img/favicon.ico'); ?>"/>
	<link rel="apple-touch-icon" href="<?php echo URL::site('media/img/favicon.png'); ?>"/>
<?php
	$styles = array(
		'media/css/vendor/bootstrap.min.css',
		'media/css/global.css',
	);
	if(isset($css) && is_array($css)) foreach($css as $c)
	{
		$styles[] = $c;
	}
	$styles[] = 'media/css/' . $controller . '.css';
	$styles[] = 'media/css/' . $controller . '/' . $action . '.css';
	echo site::css($styles, true);
	echo HTML::style('media/css/print.css', array('media'=>'print'));
?>
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body>

<div id="bg"></div>

<div class="container">
	
	<div class="row">
		<header class="col-xs-12" id="header">
			<a href="<?php echo URL::site(); ?>" id="logo" title="Morning pages">
				Morning pages
			</a>
		</header>
	</div>
	
	<div class="row" id="main">
		<section class="col-xs-12 col-md-9" id="content">
			<?php echo $view; ?>
		</section>
		<aside class="col-xs-12 col-md-3" id="sidebar">
			<h3>Welcome</h3>
			<?php if(!user::logged()): ?>
				<form role="form" method="post" action="<?php echo user::url('login'); ?>">
					<div class="form-group">
						<input type="text" class="form-control" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="E-mail or username" />
					</div>
					<div class="form-group">
						<input type="password" class="form-control" value="" name="password" placeholder="Password" />
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Log in</button>
					</div>
					<p>
						<a href="<?php echo user::url('signup'); ?>" title="Sign up">Sign up</a> - <a href="<?php echo user::url('help') ?>" title="Click here if you need help accessing your account">Forgot your password</a>?
					</p>
				</form>
				<p>
					<a href="<?php echo URL::site('auth/twitter'); ?>" title="Sign in with Twitter">
						<img src="<?php echo URL::site('media/img/sign-in-with-twitter-gray.png'); ?>" alt="Sign in with Twitter" />
					</a>
				</p>
			<?php else: ?>
				<p>
					<em>Logged in as <a href="<?php echo user::url(); ?>" title="Your profile"><?php echo user::get()->username; ?></a></em>
				</p>
				<p>
					<div class="btn-group btn-group-justified">
						<a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a>
						<?php echo HTML::anchor('user', 'Profile', array('class' => 'btn btn-default'.($controller=='User'?' active':''), 'title' => 'Profile')); ?>
						<?php echo HTML::anchor('user/logout', 'Log out', array('class' => 'btn btn-default red')); ?>
					</div>
					 
				</p>
				
				<h3>Your stats</h3>
				<table class="table">
					<tr>
						<td>Member since:</td>
						<td><?php echo date('Y',user::get()->created); ?></td>
					</tr>
					<tr>
						<td>Longest streak:</td>
						<td><?php echo user::get()->longest_streak; ?></td>
					</tr>
					<tr>
						<td>Current streak:</td>
						<td><?php echo user::get()->current_streak; ?></td>
					</tr>
					<tr>
						<td>Most words:</td>
						<td><?php echo user::get()->most_words; ?></td>
					</tr>
					<tr>
						<td>All time words:</td>
						<td><?php echo user::get()->all_time_words; ?></td>
					</tr>
				</table>
				<h3>Past posts</h3>
				<select id="pastposts" class="form-control" name="pastposts">
					<option value="0" selected="selected">Select day</option>
<?php
					$pages = user::get()->pages->where('type','=','page')->find_all();
					if((bool)$pages->count())
					{
						site::today_slug();
						foreach($pages as $page)
						{
							echo '<option value="'.$page->day.'">'.$page->date().'</option>';
						}
					}
?>
				</select>
			<?php endif; ?>
		</aside>
	</div>
	
	<footer class="row" id="footer">
		<div class="col-md-12 col-md-4">
			<h3>Morning pages</h3>
		</div>
		<nav class="col-md-12 col-md-8 text-right">
			<ul class="nav nav-pills navbar-right">
				<li>
					<a href="<?php echo URL::site(); ?>">Home</a>
				</li>
				<li>
					<?php if(!user::logged()): ?>
						<a href="<?php echo user::url('signup'); ?>">Register</a>
					<?php else: ?>
						<a href="<?php echo user::url(''); ?>">Your account</a>
					<?php endif; ?>
				</li>
				<li>
					<a href="<?php echo URL::site('suggestions'); ?>">Suggestions</a>
				</li>
				<li>
					<a href="<?php echo URL::site('faq'); ?>">FAQ</a>
				</li>
			</ul>
		</nav>
	</footer>
	
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL::site('media/js/vendor/bootstrap.min.js'); ?>"></script>
<?php
	$notes = notes::fetch();
	if($notes)
	{
		echo '<script type="text/javascript">';
		echo 'var notes = ' . json_encode($notes) . ';';
		echo '</script>'; 
	}
	$js = array(
		'media/js/jquery.autosize.js',
		URL::site('ajax/js/vars',true),
		'media/js/global.js'
	);
	if(is_array($scripts)) foreach($scripts as $file)
	{
		$js[] = $file;
	}
	$js[] = 'media/js/' . $controller . '.js';
	$js[] = 'media/js/' . $controller . '/' . $action . '.js';
	
	//if(!Session::instance()->get('cron',false))
	//{
		//$js[] = 'media/js/cron.js';
		//Session::instance()->set('cron',true);
	//}
	
	echo site::js($js);
?>
</body>
</html>
