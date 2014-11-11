<div class="row-fluid">
	<div class="span12">
		<ul class="breadcrumb">
			<li>
				<?php echo HTML::anchor('', site::name());?>
			</li>
			<li class="active">Your account</li>
		</ul>
	</div>
</div>

<div class="page-header">
	<h1><?php echo user::get()->username; ?></h1>
</div>

<p>
	You've been a member since <?php echo user::get()->created(); ?>.
</p>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#days" data-toggle="tab">Your days</a>
	</li>
	<li>
		<a href="#info" data-toggle="tab">Account info</a>
	</li>
	<li>
		<a href="#actions" data-toggle="tab">Actions</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="days">
		
		<strong>Your days:</strong>
<?php
		$start = user::get()->created;
		$years = Date::years(date('Y', $start), date('Y'));
		if(is_array($years)) foreach($years as $year)
		{
			echo '<h3>'.$year.'</h3>';
			$startofyear = mktime(0,0,0,1,1,$year);
			$endofyear = mktime(23,59,59,12,31,$year);
			$limit = 12;
			if($year == date('Y'))
			{
				$limit = date('n');
			}
			echo '<div class="panel-group" id="accordion'.$year.'">';
			for($i=$limit;$i>=1;$i--)
			{
				$startofmonth = mktime(0,0,0,$i,1,$year);
				$endofmonth = mktime(0,0,0,$i,date('t',$startofmonth),$year);
				$pages = user::get()
					->pages
					->where('type','=','page')
					->where('created','>',$startofmonth)
					->where('created','<',$endofmonth)
					->find_all();
?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion<?php echo $year; ?>" href="#collapse<?php echo $year.$i; ?>"><?php echo date('F',$startofmonth); ?></a>
						</h4>
					</div>
					<div class="panel-collapse collapse" id="collapse<?php echo $year.$i; ?>">
						<div class="panel-body">
<?php
							echo '<p><em>'.$pages->count().' page'.($pages->count()==1?'':'s').'</em></p>';
							if((bool)$pages->count())
							{
								
								echo '<ul>';
								foreach($pages as $page)
								{
									$numwords = $page->wordcount;
									echo '<li><a href="'.url::site('write/'.$page->day).'" title="See your writing from '.$page->date().'">'.$page->date().'</a> - '.$numwords.' word'.($numwords==1?'':'s').'</li>';
								}
								echo '</ul>';
							}
?>
						</div>
					</div>
				</div>
<?php
				
			}
			echo '</div>';
		}
?>
	</div>
	<div class="tab-pane" id="info">
		
		<form action="<?php echo user::url(); ?>" method="post" role="form">
			
			<div class="form-group<?php echo (arr::get($errors, 'email', false)?' has-error':''); ?>">
				<label for="account-email">Your E-mail:</label>
				<input type="email" placeholder="Your e-mail" name="email" id="account-email" class="form-control" value="<?php echo user::get()->email; ?>" />
<?php
				if(arr::get($errors, 'email', false))
				{
					echo '<span class="help-block">';
					echo arr::get($errors, 'email','');
					echo '</span>';
				}
?>
			</div>
			
			<div class="form-group<?php echo (arr::get($errors, 'reminder', false)?' has-errors':''); ?>">
				<div class="checkbox">
					<label for="account-reminder">
						<input type="checkbox" name="reminder" id="account-reminder" value="1"<?php echo ((bool)user::get()->reminder?' checked':''); ?>>
						Send me daily reminder e-mails
					</label>
				</div>
			</div>
			
			<hr />
			
			<div class="alert alert-warning">
				<strong>Heads up!</strong> Only fill out the below fields if you want to change your password.
			</div>
			
			<div class="form-group<?php echo (arr::path($errors, '_external.password', false)?' has-error':''); ?>">
				<label for="account-password">New password:</label>
				<input type="password" placeholder="Your new password" name="password" id="account-password" class="form-control" />
<?php
				if(arr::path($errors, '_external.password', false))
				{
					echo '<span class="help-block">';
					echo arr::path($errors, '_external.password','');
					echo '</span>';
				}
?>
			</div>
			
			<div class="form-group<?php echo (arr::path($errors, '_external.password_confirm', false)?' has-error':''); ?>">
				<label for="account-password-confirm">Confirm password:</label>
				<input type="password" placeholder="Confirm your new password" name="password_confirm" id="account-password-confirm" class="form-control" />
<?php
				if(arr::path($errors, '_external.password_confirm', false))
				{
					echo '<span class="help-block">';
					echo arr::path($errors, '_external.password_confirm','');
					echo '</span>';
				}
?>
			</div>
			
			<button class="btn btn-primary">Save info</button>
			
		</form>
		
	</div>
	<div class="tab-pane" id="actions">
		<h4>Actions:</h4>
		<p>
			<a id="account-deleter" href="<?php echo user::url('delete'); ?>" title="Delete your account" class="btn btn-danger">
				<span class="glyphicon glyphicon-warning-sign"></span> Delete account
			</a>
		</p>
		<div class="alert alert-danger">
			<strong>Warning!</strong> Choosing to delete your account will erase all data associated with it after seven days. If, in that seven day period, you decided to keep your account, simply login and your account will remain intact.
		</div>
	</div>
</div>
