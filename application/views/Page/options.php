<section class="options-page">
	<nav class="user-options-nav">
		<ul data-bind="tabs:'tab-container'">
		   <li class="tab active-tab">
		       <a href="#personal-settings">Personal Settings</a>
		   </li>
		   <li class="tab">
		       <a href="#site-settings">Site Settings</a>
		   </li>
		   <li class="tab">
		       <a href="#account-settings">Account Details</a>
		   </li>
		</ul>

	</nav>

	<form class="user-options-form">
		<div class="tab-container" id="personal-settings">
			<fieldset>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="text" id="email" name="email">
				</div>

				<div class="form-group">
					<label for="new-password">New Password</label>
					<input type="password" id="new-password" name="new-password">
				</div>
				<div class="form-group">
					<label for="confirm-new-password">New Password Confirm</label>
					<input type="password" id="confirm-new-password" name="confirm-new-password">
				</div>
			</fieldset>
		</div>
		<div class="tab-container" id="site-settings">
			<fieldset>
				<div class="form-group">
					<label for="site-theme" class="hidden">Theme</label>
						<select id="site-theme" name="site-theme">
							<option value="0">Current Theme</option>
							<option value="1">Name of theme</option>
							<option value="2">A secondary theme option</option>
							<option value="3">Third theme!</option>
						</select>
				</div>
				<div class="form-group button-group">
					<label for="writing-reminders-button">Receive daily reminder e-mails at the time that you specify.</label>
					<button id="writing-reminders-button" class="btn btn-bad">Daily reminder e-mails off</button>
					
					<div class="time-container">
						<select name="hour" id="reminder-hour">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option>11</option>
							<option>12</option>
						</select>
						<select name="minute" id="reminder-minute">
							<option>00</option>
							<option>15</option>
							<option>30</option>
							<option>45</option>
						</select>
						<select name="day-night" id="reminder-day-night">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>
				<div class="form-group button-group">
					<label for="privacy-mode">Privacy mode logs you out after 1, 5, or 10 minutes.</label>
					<button id="privacy-mode" class="btn btn-bad">Privacy mode off</button>
				</div>
				<div class="form-group button-group">
					<label for="hemingway-mode">Hemingway mode disables the use of the backspace key, keeping your writing pure.</label>
					<button id="hemingway-mode" class="btn btn-bad">Hemingway mode off</button>
				</div>
				<div class="form-group button-group">
					<label for="public-profile">Turn this on in order to allow others to view your profile that includes your username, badges, and stats.</label>
					<button id="public-profile" class="btn btn-bad">Public profile off</button>
				</div>
			</fieldset>
		</div>
		<div class="tab-container" id="account-settings">
			<fieldset>
				<div class="form-group">
					<label for="file" class="file">Import posts</label>
					<input type="file" name="file" id="file">
				</div>
				<div class="form-group">
					<label for="export-posts" class="hidden">Export in XML format</label>
					<button id="export-posts" class="btn btn-good">Export Posts</button> 
				</div>
				<div class="form-group">
					<a href="#" id="delete-account">Delete Account (REPLACE THE SAVE BUTTON WITH THIS SOME HOW)</a>
				</div>
			</fieldset>
		</div>

		<div class="pull-right">
			<button name="account-deletion" class="btn-good pull-right">Save info</button>
		</div>
	</form>
</section> <!-- options modal -->