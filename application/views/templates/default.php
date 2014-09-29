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
					<li><a href="<?php echo URL::site('write'); ?>" title="Write" class="btn btn-default<?php echo ($controller=='Page'?' active':''); ?>">Write</a></li>
					<li><a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">Talk</a></li>
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
										<input type="text" class="form-control" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="Email or Username" />
									</div>
									<div class="form-group">
										<input type="password" class="form-control" value="" name="password" placeholder="Password" />
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
										<input type="text" class="form-control" name="email" value="<?php echo arr::get($_POST, 'email',''); ?>" placeholder="E-mail" />
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="username" value="<?php echo arr::get($_POST, 'username',''); ?>" placeholder="Username" />
									</div>
									<div class="form-group">
										<input type="password" class="form-control" value="" name="password" placeholder="Password" />
									</div>
									<div class="form-group">
										<input type="password" class="form-control" value="" name="password_confirm" placeholder="Password Confirm" />
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
					<button id="hidden-nav-trigger" class="navigation-trigger" data-bind="click:hamburgerClick"><span class="fa fa-cog"></span></button>
			<?php endif; ?>
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
					
					<li>
						<div class="modal">
						  <label for="tips-tricks">
						    <div class="js-btn btn">Tips and Tricks</div>
						  </label>
						  <input class="modal-state" id="tips-tricks" type="checkbox" />
							<div class="modal-window">
							<div class="modal-inner">
								<label class="modal-close" for="tips-tricks"></label>
								<h3>Tips and tricks</h3>
								<dl>
									<dt>Ctrl/Cmd + Spacebar</dt>
									<dd>We take privacy very seriously. Use this shortcut if you're in the middle of writing and somebody walks into the room, begins to peer over your shoulder, or tries to see what you're writing. This will bring up the text from a random Wikipedia article.</dd>
					
									<dt>Markdown</dt>
									<dd>What is markdown? Markdown is simply a text-formatting syntax used to format text on the web without having to worry about HTML. Think of it as an easy and natural way to to format your text without the need of using learning code.</dd>
									<dd>Although the idea behind Morning Pages is stream of consciousness thoughts, sometimes those thoughts would be better off with a bit of organization and that's where Markdown comes in handy.</dd>
									<dd>For an in-depth listing of all of Markdown's features, check out <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank" rel="nofollow">Markdown Syntax Basics</a>.</dd>
									</dd>
								</dl>
								</div>
							</div>
						</div>
					</li>
					<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
				<?php else: ?>
					<li><a href="<?php echo URL::site('options'); ?>" data-bind="showModal:'shortcuts-modal'" id="js-show-tips">Shortcuts</a></li>
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
		require(['project'], function(){
			<?php if($include_viewmodel): ?>
				require(['viewModels/<?php echo $controller.'/'.$action; ?>']);
			<?php endif; ?>
		});
	</script>
</body>
</html>
