<h2>Wall of fame!</h2>


<?php
	$options = ORM::factory('User_Option')
		->where('completedchallenge', '!=', 0)
		->order_by('completedchallenge', 'ASC')
		->find_all();
	if((bool)$options->count())
	{
?>
		<p>The following users have completed the 30 day challenge!</p>
		<table class="table-borders">
			<thead>
				<tr>
					<th>Username</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach($options as $option)
				{
					echo '<tr>';
					echo '<td>'.$option->user->link.'</td>';
					echo '<td>'.date('F jS, Y', $option->completedchallenge).'</td>';
					echo '</tr>';
				}
?>
			</tbody>
		</table

<?php
	}
	else
	{
?>
		<div class="intro-text">
			<h3><?php echo silly::ruhroh(); ?></h3>
			<h2>The wall is empty!</h2>
			<h4>Be the first to add your name to the wall of fame by completing the <?php echo HTML::anchor('challenge', '30 day challenge'); ?>!</h4>
		</div>
<?php
	}
?>
