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
			if((bool)$users->count())
			{
				foreach ($users as $user)
				{
					echo '<tr>';
				    echo '<td>'.$user->link().'</td>';
				    echo '<td>'.$user->current_streak.'</td>';
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