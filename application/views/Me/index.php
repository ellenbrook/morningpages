<div class="container">
	<section class="me">
		<h2>me/<?php echo user::get()->username; ?></h2>
		<div class="me-icon">
			<img src="<?php echo user::get()->gravatar(150); ?>" alt="Profile photo for <?php echo user::get()->username; ?>">
		</div>
		<div class="me-username">
			<p>Member since <?php echo user::get()->created(); ?></p>
		</div>

		<div class="me-stats">
			<dl>
				<div class="stat-block">
					<dt>Longest Streak</dt>
					<dd>
						<span class="stat-circle one"><?php echo number_format(user::get()->longest_streak); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>Current Streak</dt>
					<dd>
						<span class="stat-circle two"><?php echo number_format(user::get()->current_streak); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>Longest post</dt>
					<dd>
						<span class="stat-circle three"><?php echo number_format(user::get()->most_words); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>All time words</dt>
					<dd>
						<span class="stat-circle four"><?php echo number_format(user::get()->all_time_words); ?></span>
					</dd>
				</div>
			</dl>
		</div>
		<div class="me-badges">
			<h2>badge showcase</h2>
		</div>
	<?php
		
		/*$userachievements = user::get()->userachievements->find_all();
		if((bool)$userachievements->count())
		{
			foreach($userachievements as $userachievement)
			{
				echo HTML::image('media/img/badges/'.$userachievement->achievement->badge);
			}
		}*/
	?>

	</section>
</div>
