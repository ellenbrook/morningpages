<!DOCTYPE html>
<html lang="en">
<head>
	<title>Morning Pages :: Write</title>
	<script src="//use.typekit.net/rod6iku.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
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
	<header id="header" class="site-header">
		<div class="container">
			<h1 class="logo pull-left">
				<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
			</h1>
				<button class="navigation-trigger" data-bind="click:hamburgerClick">&#9776;</button>
			<div id="user-options-triangle" class="triangle pull-right"></div>
		</div>
	</header>

	<section id="user-options" class="hidden-menu">
		<div class="container">
			<ul>
				<?php if(user::logged()): ?>
					<li>Words written<br><?php echo user::get()->all_time_words; ?></li>
					<li>Current streak<br><?php echo user::get()->current_streak; ?></li>
					<li>Longest streak<br><?php echo user::get()->longest_streak; ?></li>
					<li>
				    	<select data-bind="event:{change:goToPreviousPage}" id="pastposts" class="form-control">
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
	  <label for="modal-1">
	    <div class="js-btn btn">Tips and Tricks</div>
	  </label>
	  <input class="modal-state" id="modal-1" type="checkbox" />
		<div class="modal-window">
		<div class="modal-inner">
			<label class="modal-close" for="modal-1"></label>
			<h3>Tips and tricks</h3>
			<p class="body">
			<dl>
			<dt>Ctrl/Cmd + Spacebar<dt> - <dd>We take privacy very seriously. Use this shortcut if you're in the middle of writing and somebody walks into the room, begins to peer over your shoulder, or tries to see what you're writing. This will bring up the text from a random Wikipedia article.</dd>
			</dl>
			<h4>Markdown</h4>
			<p>What is markdown? Markdown is simply a text-formatting syntax used to format text on the web without having to worry about HTML. Think of it as an easy and natural way to to format your text without the need of using learning code.</p>
			<p>Although the idea behind Morning Pages is stream of consciousness thoughts, sometimes those thoughts would be better off with a bit of organization and that's where Markdown comes in handy.</p>
			<p>For an in-depth listing of all of Markdown's features, check out <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank" rel="nofollow">Markdown Syntax Basics</a>.</p>
			</p>
			</div>
		</div>
	</div>
					</li>
					<li><a href="<?php echo URL::site('options'); ?>">Edit user options</a></li>
				<?php else: ?>
					<li>Words written<br>0</li>
					<li>Current streak<br>0</li>
					<li>Longest streak<br>0</li>
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
