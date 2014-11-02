<div class="container">
	<section class="me">
		<h2>me/<?php echo $user->username; ?></h2>
		<div class="me-icon">
			<img src="<?php echo $user->gravatar(150); ?>" alt="Profile photo for <?php echo $user->username; ?>">
		</div>
		<div class="me-username">
			<p>Member since <?php echo $user->created(); ?></p>
		</div>

		<div class="me-stats">
			<dl>
				<div class="stat-block">
					<dt>Longest Streak</dt>
					<dd>
						<span class="stat-circle one"><?php echo number_format($user->longest_streak); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>Current Streak</dt>
					<dd>
						<span class="stat-circle two"><?php echo number_format($user->current_streak); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>Longest Single Page</dt>
					<dd>
						<span class="stat-circle three"><?php echo number_format($user->most_words); ?></span>
					</dd>
				</div>
				<div class="stat-block">
					<dt>Accumulative Words</dt>
					<dd>
						<span class="stat-circle four"><?php echo number_format($user->all_time_words); ?></span>
					</dd>
				</div>
			</dl>
		</div>
		<div class="me-badges">
			<div class="badge-header">
				<h3>User badges</h3>
			</div>
			<div class="badge-container">
<?php
		
				$userachievements = $user->userachievements->find_all();
				if((bool)$userachievements->count())
				{
					foreach($userachievements as $userachievement)
					{
						echo '<div class="badge">';
						echo HTML::image('media/img/badges/'.$userachievement->achievement->badge, array(
							'title' => $userachievement->achievement->description,
							'alt' => $userachievement->achievement->description
						));
						echo '<div class="achievement-date">'.date('m-d-Y',$userachievement->created).'</div>';
						echo '</div>';
					}
				}
?>
<?php /*
				<div class="badge">
					<?php echo HTML::image('media/img/badges/newbie.png'); ?>
				</div>

				<div class="badge">
					<?php echo HTML::image('media/img/badges/three-in-a-row.png'); ?>
				</div>

				<div class="badge">
					<?php echo HTML::image('media/img/badges/seven-in-a-row.png'); ?>
				</div>

				<div class="badge">
					<?php echo HTML::image('media/img/badges/thirty-in-a-row.png'); ?>
				</div>

				<div class="badge">
					<?php echo HTML::image('media/img/badges/thirty-in-a-row.png'); ?>
				</div>
*/ ?>
			</div>
		</div>

	</section>
</div>
