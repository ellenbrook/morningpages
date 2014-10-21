<ul class="accordion-tabs" id="site-options">
	<li class="tab-header-and-content">
		<a href="#personal-settings" class="tab-link">Personal Settings</a>
		<div class="tab-content">
			<form class="user-options-form" data-bind="validateForm:true" action="<?php echo URL::site('user/options'); ?>" method="post">
				<fieldset>
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" required class="<?php echo (($errors&&arr::get($errors,'email',false))?'error':''); ?>" id="user-email" value="<?php echo user::get()->email; ?>" name="email">
<?php
						if($errors&&arr::get($errors,'email',false))
						{
							echo '<label for="user-email" class="error">';
							$errs = arr::get($errors,'email');
							if(is_array($errs))
							{
								echo '<ul>';
								foreach($errs as $err)
								{
									echo '<li>'.$err.'</li>';
								}
								echo '</ul>';
							}
							else
							{
								echo $errs;
							}
							echo '</label>';
						}
?>
					</div>
					
					<hr />
					
					<div class="flash-notice">
						<h4>Note</h4> only fill out the below fields if you wan't to change your password.
					</div>
	
					<div class="form-group">
						<label for="new-password">New Password</label>
						<input minlength="5" title="Min 5, max 42 characters" class="<?php echo (($errors&&arr::get($errors,'password',false))?'error':''); ?>" type="password" id="new-password" placeholder="Enter your new password.." name="password">
<?php
						if($errors&&arr::path($errors, '_external.password', false))
						{
							echo '<label for="new-password" class="error">';
							$errs = arr::path($errors, '_external.password');
							if(is_array($errs))
							{
								echo '<ul>';
								foreach($errs as $err)
								{
									echo '<li>'.$err.'</li>';
								}
								echo '</ul>';
							}
							else
							{
								echo $errs;
							}
							echo '</label>';
						}
?>
					</div>
					<div class="form-group">
						<label for="confirm-new-password">Confirm your new password</label>
						<input minlength="5" title="Must match above password" class="<?php echo (($errors&&arr::get($errors,'password_confirm',false))?'error':''); ?>" type="password" placeholder="Re-enter your new password.." id="confirm-new-password" name="password_confirm">
<?php
						if($errors&&arr::path($errors, '_external.password_confirm', false))
						{
							echo '<label for="confirm-new-password" class="error">';
							$errs = arr::path($errors, '_external.password_confirm');
							if(is_array($errs))
							{
								echo '<ul>';
								foreach($errs as $err)
								{
									echo '<li>'.$err.'</li>';
								}
								echo '</ul>';
							}
							else
							{
								echo $errs;
							}
							echo '</label>';
						}
?>
					</div>
					
					<div class="form-group text-right">
						<button class="btn-good pull-right">Save personal settings</button>
					</div>
					
				</fieldset>
			</form>
		</div>
	</li>
 	<li class="tab-header-and-content">
		<a href="#site-settings" class="tab-link">Site Settings</a>
		<div class="tab-content">
			<p>
				<em>Note: The below settings save automatically when you change them</em>
			</p>
  			<fieldset>
				<div class="form-group">
					<label for="site-theme" class="hidden">Theme</label>
						<label for="site-theme">Site theme</label>
						<select data-bind="value:user.options.theme_id,event:{change:save_theme}" id="site-theme" name="theme_id">
							<option value="0">Standard</option>
<?php
							$themes = ORM::factory('Theme')->find_all();
							if((bool)$themes->count())
							{
								foreach($themes as $theme)
								{
									echo '<option value="'.$theme->id.'">'.$theme->title.'</option>';
								}
							}
?>
						</select>
				</div>
				<div class="form-group button-group">
					<label for="writing-reminders-button">Receive daily reminder e-mails at the time that you specify.</label>
					
					<label class="label-switch">
						<input type="checkbox" data-bind="checked:user.options.reminder,event:{change:function(){save_setting('reminder');}}" />
							<div class="checkbox"></div>
					</label>
					<label class="status" data-bind="css:{'on':user.options.reminder(),'off':!user.options.reminder()}">
						<span data-bind="visible:user.options.reminder()">ON</span>
						<span data-bind="visible:!user.options.reminder()">OFF</span>
					</label>
					
					<div class="inline-form time-container" data-bind="fadeVisible:user.options.reminder()">
						<select data-bind="value:user.options.reminder_hour,event:{change:function(){save_setting('reminder_hour');}}" name="hour" id="reminder-hour">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						<select data-bind="value:user.options.reminder_minute,,event:{change:function(){save_setting('reminder_minute');}}" name="minute" id="reminder-minute">
							<option value="0">00</option>
							<option value="15">15</option>
							<option value="30">30</option>
							<option value="45">45</option>
						</select>
						<select data-bind="value:user.options.reminder_meridiem,event:{change:function(){save_setting('reminder_meridiem');}}" name="day-night" id="reminder-day-night">
							<option value="am">AM</option>
							<option value="pm">PM</option>
						</select>
						
						<select name="timezone" data-bind="value:user.options.timezone_id,event:{change:function(){save_setting('timezone_id');}}" id="reminder-timezone">
<?php
							$zones = ORM::factory('Timezone')->find_all();
							foreach($zones as $zone)
							{
								echo '<option value="'.$zone->id.'">'.$zone->name.'</option>';
							}
?>
						</select>
						
					</div>
				</div>
				<div class="form-group button-group">
					<label for="privacy-mode">Privacy mode logs you out after 5, 10 or 15 minutes of inactivity.</label>
					
					<label class="label-switch">
						<input type="checkbox" data-bind="checked:user.options.privacymode,event:{change:function(){save_setting('privacymode');}}" />
							<div class="checkbox"></div>
					</label>
					<label class="status" data-bind="css:{'on':user.options.privacymode(),'off':!user.options.privacymode()}">
						<span data-bind="visible:user.options.privacymode()">ON</span>
						<span data-bind="visible:!user.options.privacymode()">OFF</span>
					</label>
					
					<div class="inline-form" data-bind="fadeVisible:user.options.privacymode()">
						<select data-bind="value:user.options.privacymode_minutes,event:{change:function(){save_setting('privacymode_minutes');}}" name="privacymode_minutes" id="privacymode-minutes">
							<option value="5">5 minute</option>
							<option value="10">10 minutes</option>
							<option value="15">15 minutes</option>
						</select>
					</div>
					
				</div>
				<div class="form-group button-group">
					<label for="hemingway-mode">Hemingway mode disables the use of the backspace key, keeping your writing pure.</label>
					
					<label class="label-switch">
						<input type="checkbox" data-bind="checked:user.options.hemingwaymode,event:{change:function(){save_setting('hemingwaymode');}}" />
							<div class="checkbox"></div>
					</label>
					<label class="status" data-bind="css:{'on':user.options.hemingwaymode(),'off':!user.options.hemingwaymode()}">
						<span data-bind="visible:user.options.hemingwaymode()">ON</span>
						<span data-bind="visible:!user.options.hemingwaymode()">OFF</span>
					</label>
					
				</div>
				<div class="form-group button-group">
					<label for="public-profile">Turn this on in order to allow others to view your profile that includes your username, badges, and stats.</label>
					
					<label class="label-switch">
						<input type="checkbox" data-bind="checked:user.options.public,event:{change:function(){save_setting('public');}}" />
							<div class="checkbox"></div>
					</label>
					<label class="status" data-bind="css:{'on':user.options.public(),'off':!user.options.public()}">
						<span data-bind="visible:user.options.public()">ON</span>
						<span data-bind="visible:!user.options.public()">OFF</span>
					</label>
					
				</div>
			</fieldset>
		</div>
	</li>
 	<li class="tab-header-and-content">
		<a href="#account-settings" class="tab-link is-active">Account Details</a>
		<div class="tab-content">
			<fieldset>
				<div class="form-group">
					<h3>Export your pages</h3>
					<label for="export-posts" class="hidden">Export in XML format</label>
					<a href="<?php echo URL::site('ajax/export/xml'); ?>" id="export-posts" class="btn btn-good">Export <span class="fa fa-share"></span></a> 
				</div>
				<hr />
				<div class="form-group">
					<h3>Danger zone</h3>
					<p>
						<em>Remember you can export your pages above if you want to save them for yourself.</em>
					</p>
					<p>
						<a href="#" id="delete-account" data-bind="click:deleteAccount" class="btn btn-bad">Delete Account <span class="fa fa-frown-o"></span></a>
					</p>
				</div>
			</fieldset>    
		</div>
	</li>
</ul>

<?php echo View::factory('modals/delete-account'); ?>
