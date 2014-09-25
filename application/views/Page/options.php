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
					<label for="site-theme">Theme</label>
						<select id="site-theme" name="site-theme">
							<option value="1">Theme 1</option>
							<option value="2">Theme 2</option>
							<option value="3">Theme 3</option>
						</select>
				</div>
				<div class="form-group">
				<input type="checkbox" class="checkbox-button" name="writing-reminders-button" id="writing-reminders-button">
				<label for="writing-reminders-button" class="button">Daily e-mail reminders</label>
					 <br>
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
					</select>:<select name="minute" id="reminder-minute">
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
				<div class="form-group">
				<label for="privacy-mode">Enable Privacy Mode</label>
					<input id="privacy-mode" name="privacy-mode" type="checkbox">
				</div>
				<div class="form-group">
				<label for="hemingway-mode">Enable Hemingway Mode</label>
					<input id="hemingway-mode" id="hemingway-mode" type="checkbox">
				</div>
				<div class="form-group">
				<label for="public-profile">Make my profile public</label>
					<input id="public-profile" type="checkbox" name="public-profile">
				</div>
			</fieldset>
		</div>
		<div class="tab-container" id="account-settings">
			<fieldset>
				<label for="file">Filename:</label>
					<input type="file" name="file" id="file">
				<br>
				<button class="btn btn-good">Export Posts (XML format)</button> 
				<br>
				<a href="#">Delete Account</a>
			</fieldset>
		</div>

		<div class="pull-right">
			<button name="account-deletion" class="btn-good pull-right">Save info</button>
		</div>
	</form>
</section> <!-- options modal -->