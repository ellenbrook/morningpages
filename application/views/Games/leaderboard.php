<div class="half">
	<h2>Current Streak</h2>

	<table class="table-borders">
		<thead>
			<tr>
				<th>Username</th>
				<th>Current Streak</th>
			</tr>
		</thead>
		<tbody>
<?php
			if((bool)$streaks->count())
			{
				foreach ($streaks as $streakuser)
				{
					echo '<tr>';
				    echo '<td>'.$streakuser->link().'</td>';
				    echo '<td>'.$streakuser->current_streak.'</td>';
				    echo '</tr>';
				}
			}
?>
		</tbody>
	</table>
</div>

<div class="half">
	<h2>Most active on <a href="/talk">/talk</a></h2>
	<table class="table-borders">
		<thead>
			<tr>
				<th>Username</th>
				<th>Posts</th>
			</tr>
		</thead>
		<tbody>
<?php
			foreach($active as $a)
		    {
		        $user = ORM::factory('user', arr::get($a, 'id'));
		        if($user->loaded())
		        {
		           echo '<tr>';
		           echo '<td>'.$user->link().'</td>';
		           echo '<td>'.arr::get($a, 'posts', 0).'</td>';
		           echo '</tr>';
		        }
		    }
?>
		</tbody>
	</table>
</div>

<hr />

<div class="half">
	<h2>Most points</h2>
	<table class="table-borders">
		<thead>
			<th>Username</th>
			<th>Points</th>
		</thead>
		<tbody>
<?php
			foreach($points as $point)
		    {
		        $user = ORM::factory('user', arr::get($point, 'id'));
		        if($user->loaded())
		        {
		           echo '<tr>';
		           echo '<td>'.$user->link().'</td>';
		           echo '<td>'.arr::get($point, 'points', 0).'</td>';
		           echo '</tr>';
		        }
		    }
?>
		</tbody>
	</table>
</div>
