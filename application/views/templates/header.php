<?php

$isfront = routes::isFrontPage();

?>

<header id="header" class="index-header">
	<div class="container">
		<h1 class="logo pull-left">
			<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
		</h1>
		<div data-bind="ImAburger:true" class="hamburger-container">
			<div class="hamburger-line"></div>
			<div class="hamburger-line"></div>
			<div class="hamburger-line"></div>
		</div>
		<div id="mobile-nav" class="mobile-navigation">
			<ul>
				<li class="header">Navigation</li>
				<li><a href="#about">About</a></li>
				<li><a href="<?php echo URL::site('write'); ?>" title="Write">Write<?php echo (user::logged()?(' ('.user::get()->current_streak.')'):''); ?></a></li>
				
				<li class="<?php echo (user::logged()?'hidden':''); ?>" data-bind="if:!site.user.logged(),css:{'shown':!site.user.logged()}">
					<ul>
						<li><a href="<?php echo user::url('login'); ?>" data-bind="showModal:{element:'#loginModal',done:site.doneLoggingIn}">Login</a></li>
						<li><a href="<?php echo user::url('signup'); ?>" data-bind="showModal:{element:'#registerModal',done:site.doneRegistering}">Register</a></li>
					</ul>
				</li>
				<li class="<?php echo (!user::logged()?'hidden':''); ?>" data-bind="if:site.user.logged(),css:{'shown':site.user.logged()}">
					<ul>
						<li><a href="<?php echo URL::site('me'); ?>" title="Your personal Morning Pages profile">Me</a></li>
						<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
						<li>
							<a href="<?php echo URL::site('user/options'); ?>" title="User options"><span class="fa fa-cog"></span></a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<nav class="<?php echo ($isfront?'frontpage':'default');  ?>">
			<ul>
				<li><a href="<?php echo URL::site('about'); ?>" title="About Morning Pages">About</a></li>
				<li><a href="<?php echo URL::site('write'); ?>" title="Write">Write<?php echo (user::logged()?(' ('.user::get()->current_streak.')'):''); ?></a></li>
				<li class="<?php echo (user::logged()?'hidden':''); ?>" data-bind="if:!site.user.logged(),css:{'shown':site.user.logged()}">
					<ul>
						<li><a href="<?php echo user::url('login'); ?>" data-bind="showModal:{element:'#loginModal',done:site.doneLoggingIn}">Login</a></li>
						<li><a href="<?php echo user::url('signup'); ?>" data-bind="showModal:{element:'#registerModal',done:site.doneRegistering}">Register</a></li>
					</ul>
				</li>
				<li class="<?php echo (!user::logged()?'hidden':''); ?>" data-bind="if:site.user.logged(),css:{'shown':site.user.logged()}">
					<ul>
						<li><a href="<?php echo URL::site('me'); ?>" title="Your personal Morning Pages profile">Me</a></li>
						<li><a href="<?php echo URL::site('user/logout'); ?>" title="Log out">Log out</a></li>
						<li>
							<a href="<?php echo URL::site('user/options'); ?>" title="User options"><span class="fa fa-cog"></span></a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</header>

<?php if(false): ?>
	
	<section id="user-options" class="hidden-menu">
		<div class="container">
			<ul>
				<?php if(user::logged()): ?>
					<li><a href="<?php echo URL::site('user/options'); ?>">User options</a></li>
					<!-- <li>Current streak: echo user::get()->current_streak </li> -->
					<li>
				    	<select data-bind="goToPreviousPage:true" id="pastposts">
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
	                                    if($day->day != site::day_slug())
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
				<?php else: ?>
					<li><a href="#" data-bind="showModal:'#tips-and-tricks'">Tips and Tricks</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</section>
	
<?php endif; ?>
