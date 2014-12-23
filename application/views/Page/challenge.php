<div id="challenge">
	<h2>Take the 30 day Morning Page challenge!</h2>
	
	<div class="hide intro" data-bind="css:{hide:user.doingChallenge()}">
		<p>
			Can you write every day for a whole month? Sign up today and write every day until the <?php echo date('jS', strtotime('+30 days')).' of '.date('F', strtotime('+30 days')); ?> to complete the challenge.
		</p>
	</div>
	
	<div class="hide" data-bind="css:{hide:!user.logged()}">
		<div data-bind="if:!user.doingChallenge()">
			<div class="signup">
				<button data-bind="click:confirmSignup">
					<span class="fa fa-sign-in"></span>
					Take the challenge!
				</button>
			</div>
		</div>
	</div>
	
	<div class="hide loginbtn" data-bind="css:{hide:user.logged}">
		<p><em>But first:</em></p>
		<button data-bind="showModal:{element:'#loginModal',done:site.doneLoggingIn}">Log in</button> or <a href="#" data-bind="showModal:{element:'#registerModal',done:site.doneRegisterring}">sign up</a>
	</div>
	
	<div class="hide personalprogress" data-bind="css:{hide:!user.doingChallenge()}">
		<div>Your progress:</div>
		<div class="numbers">
			<span data-bind="text:user.challengeProgress()"></span> / 30
		</div>
		<div class="hide awesome" data-bind="css:{hide:user.challengeProgress()==0}">Awesome!</div>
	</div>
	
	<div class="hide checkmark">
		<span class="fa fa-check-square"></span>
	</div>
	
	<h3>Users currently doing the 30 day challenge:</h3>
	
<?php
	$challenges = ORM::factory('User_Challenge')
		->order_by('progress', 'DESC')
		->find_all();
		
	if((bool)$challenges->count())
	{
		foreach($challenges as $challenge)
		{
?>
			<div class="userprogress">
				<div class="name">
					<?php echo $challenge->user->link(); ?>
				</div>
				<div class="progress">
					<div class="bar" style="width:<?php echo floor(($challenge->progress / 30)*100); ?>%"></div>
					<div class="numbers"><?php echo $challenge->progress; ?> / 30</div>
				</div>
			</div>
<?php
		}
	}
	else
	{
		echo '<p>Nobody is currently doing the challenge! Be the first to sign up!</p>';
	}
?>
	
</div>