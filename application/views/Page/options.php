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
		       <a href="#account-settings">Account Settings</a>
		   </li>
		</ul>

	</nav>

	<form>
		<div class="tab-container" id="personal-settings">
			<fieldset>
				<legend>User Options</legend>

				<label for="email">Email</label>
					<input type="text" id="email" name="email">

				<label for="new-password">New Password</label>
					<input type="password" id="new-password" name="new-password">

				<label for="confirm-new-password">New Password Confirm</label>
					<input type="password" id="confirm-new-password" name="confirm-new-password">
			</fieldset>
		</div>
		<div class="tab-container" id="site-settings">
			<fieldset>
				<legend>Site Options</legend>

				<label for="site-theme">Theme</label>
					<select id="site-theme" name="site-theme">
						<option value="1">Theme 1</option>
						<option value="2">Theme 2</option>
						<option value="3">Theme 3</option>
					</select>

				<label for="reminder-email">Daily email reminder</label>
					<input id="reminder-email" type="checkbox" name="reminder-email">
					 at 
					<select>
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
					</select>:<select>
						<option>00</option>
						<option>15</option>
						<option>30</option>
						<option>45</option>
					</select>
					<select>
						<option>AM</option>
						<option>PM</option>
					</select>

				<label for="privacy-mode">Privacy Mode</label>
					<input id="privacy-mode" name="privacy-mode" type="checkbox">

				<label for="hemingway-mode">Hemingway Mode</label>
					<input id="hemingway-mode" id="hemingway-mode" type="checkbox">

			</fieldset>
		</div>
		<div class="tab-container" id="account-settings">
			<fieldset>
				<legend>User Options</legend>

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