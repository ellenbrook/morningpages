<div class="container">
	<section class="me">
		<h2>me/<?php echo $user->username; ?></h2>
		<div class="me-icon">
			<img src="<?php echo $user->gravatar(150); ?>" alt="Profile photo for <?php echo $user->username; ?>">
		</div>
		<div class="me-username">
			<p>Member since <?php echo $user->created(); ?></p>
			<?php if(user::logged() && user::get()->id==$user->id): ?>
				<select data-bind="goToPreviousPage:true" id="pastposts">
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
       		<?php endif; ?>
		</div>

		<div class="me-stats">
			<div class="stats-header">
				<h3>User stats</h3>
			</div>
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
