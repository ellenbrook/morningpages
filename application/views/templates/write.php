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
	<link rel="stylesheet" type="text/css" id="mainstyles" href="<?php echo URL::site('media/css/vendor/jgrowl.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" id="mainstyles" href="<?php echo URL::site('media/css/style.css'); ?>" />
</head>
<body>

	<header id="header">
		<div class="container">
			<h1 class="logo pull-left">
				<a href="<?php echo URL::site(); ?>" title="Morning pages">Morning Pages</a>
			</h1>
			<div class="pull-right">
				<button class="btn-hamburger" data-bind="click:hamburgerClick">&#9776;</button>
			</div>
			<div id="user-options-triangle" class="triangle pull-right"></div>
		</div>
	</header>

	<section id="user-options" class="user-options">
		<div class="container">
			<ul class="user-menu">
				<li>Words written<br><?php echo user::get()->all_time_words; ?></li>
				<li>Current streak<br><?php echo user::get()->current_streak; ?></li>
				<li>Longest streak<br><?php echo user::get()->longest_streak; ?></li>
				<li>
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
					
					echo '<select data-bind="event:{change:goToPreviousPage}" id="pastposts" class="form-control" >';
					echo '<option value="0">Previous pages</option>';
					foreach($years as $year => $month)
					{
						foreach($month as $monthname => $days)
						{
							echo '<optgroup label="'.$monthname.', '.$year.'">';
							foreach($days as $day)
							{
							    $dayname = date('l ',$day->created).' the '.date('jS',$day->created);
                                if($day->day == site::today_slug())
                                {
                                    $dayname = 'Today';
                                }
								echo '<option value="'.$day->day.'"'.($daystamp==$day->day?' selected="selected"':'').'>'.$dayname.'</option>';
							}
							echo '<optgroup>';
						}
					}
					echo '</select>';
?>
				</li>
				<li><a href="#" data-bind="showModal:'shortcuts-modal'" id="js-show-tips">Shortcuts</a></li>
				<li><a href="#" data-bind="showModal:'user-options-modal'" id="js-edit-profile">Edit user options</a></li>
			</ul>
		</div>
	</section>
	
	<article>
	    <div id="dummy-content" class="hidden">
            <?php echo ORM::factory('Dummytext')->order_by(DB::expr('RAND()'))->limit(1)->find()->text(); ?>
        </div>
		<div id="page-content" data-wordcount="<?php echo $page->wordcount(); ?>" class="container" data-id="<?php echo $page->id; ?>">
			<h2 class="date"><?php echo date('F d, Y', $page->created) ?></h2>
			<?php if($page->content != ''): ?>
				<figure class="wordcloud pull-right">
					<div class="wordcloud-heading">
						<h3 class="wordcloud-title">Today's Words</h3>
					</div>
					<div class="wordcloud-body">
						<?php echo $page->wordcloud(); ?>
					</div>
				</figure>
			<?php endif; ?>
			<?php echo $page->content(); ?>
		</div>
	</article>

	<?php if($page->open()): ?>
		<section>
			<div class="container" id="writing-container">
				<form role="form" action="<?php echo URL::site('write/'.$page->day); ?>" method="post" id="writeform">
					<textarea name="morningpage" autofocus data-bind="value:writtenwords,valueUpdate:'keyup',autogrow:''"></textarea>
					<button class="btn-good pull-right" data-bind="disable:totalwords()<750,css:{'btn-disabled':totalwords()<750}">Submit</button>
					<p class="subtext pull-left">
					      <span data-bind="text:wordcount()">0</span> / 750
                    </p>
				</form>
			</div>
		</section>
	<?php endif; ?>
	<div class="modal-overlay"></div>	
	<section id="user-options-modal" class="user-options-modal">
		<header>
			<h3 class="pull-left">User Options</h3>
			<button data-bind="hideModal:'user-options-modal'" class="btn-close pull-right">&#x2715</button>
		</header>
		<div class="user-options-modal-body">
			<form action="/user" data-bind="validateForm:saveUserInfo" method="post" role="form">
				<div class="form-container">
					<div class="form-group">
						<label for="account-email">Your E-mail:</label>
						<input type="email" placeholder="Your e-mail" name="email" id="account-email" class="form-control" data-bind="value:user.email" required>
					</div>
	
					<div class="form-group">
						<label for="account-email">Your theme:</label>
						<select id="pastposts" data-bind="value:user.theme" class="form-control" name="theme">
							<option value="standard">Standard</option>
							<option value="future">Future</option>
							<option value="trendy">Trendy Name</option>
							<option value="calm">Calm</option>	
						</select>
					</div>
				</div>
	
					<div class="form-container">				
						<div class="form-group">
							<label for="account-password">New password:</label>
							<input type="password" minlength="5" data-bind="value:user.password" equalto="#account-password-confirm" placeholder="Your new password" name="password" id="account-password" class="form-control">
						</div>
						
						<div class="form-group">
							<label for="account-password-confirm">Confirm new password:</label>
							<input type="password" minlength="5" equalto="#account-password" data-bind="value:user.passconfirm" placeholder="Confirm your new password" name="password_confirm" id="account-password-confirm" class="form-control">
						</div>
					</div>	
	
					<div class="form-container">
						<div class="form-group">
							<label for="reminder" class="inline">Daily reminders?</label>
							<input type="checkbox" data-bind="checked:user.reminder" name="reminder" id="account-reminder" class="inline">
						</div>
					</div>
	
					<div class="form-group pull-left">
						<a href="/user/delete" title="Delete your account" class="btn btn-bad">Delete account</a>
					</div>
					
					<div class="form-group pull-right">
						<button name="account-deletion" class="btn-good pull-right">Save info</button>
					</div>
				</form>
		</div>
	</section>

	<section id="shortcuts-modal" class="shortcuts-modal">
		<header>
			<h3 class="pull-left">Tips and tricks</h3>
			<button data-bind="hideModal:'shortcuts-modal'" class="btn-close pull-right">&#x2715</button>
		</header>
		<div class="shortcuts-modal-body">
			<h3>Privacy Mode</h3>
			<dl class="modal-list">
				<dt>Ctrl/Cmd + Spacebar<dt> - <dd>We take privacy very seriously. Use this shortcut if you're in the middle of writing and somebody walks into the room, begins to peer over your shoulder, or tries to see what you're writing. This will bring up the text from a random Wikipedia article.</dd>
			</dl>
			<h3>Markdown</h3>
			<p>What is markdown? Markdown is simply a text-formatting syntax used to format text on the web without having to worry about HTML. Think of it as an easy and natural way to to format your text without the need of using learning code.</p>
			<p>Although the idea behind Morning Pages is stream of consciousness thoughts, sometimes those thoughts would be better off with a bit of organization and that's where Markdown comes in handy.</p>
			<p>For an in-depth listing of all of Markdown's features, check out <a href="http://daringfireball.net/projects/markdown/syntax" rel="nofollow">Markdown Syntax Basics</a>.</p>
		</div>
	</section>
	<script src="<?php echo URL::site('media/js/require.js'); ?>" data-main="/media/js/write" type="text/javascript"></script>
	<?php /*
	<script src="<?php echo URL::site('media/js/vendor/knockout.js'); ?>" type="text/javascript"></script>
	<script src="//code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="<?php echo URL::site('media/js/vendor/jquery.validate.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo URL::site('media/js/vendor/jquery.autosize.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo URL::site('media/js/bindings.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo URL::site('media/js/write.js'); ?>" type="text/javascript"></script>
	 */ ?>
</body>
</html>
