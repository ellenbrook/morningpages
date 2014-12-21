<h2>Leaderboard</h2>

<table class="table-borders">
  <thead>
    <tr>
      <th>Rank</th>
      <th>Username</th>
      <th>Current Streak</th>
    </tr>
  </thead>
  <tbody>
<?php

$users = ORM::factory('User')
	->order_by('current_streak', 'DESC')
	->limit(20)
	->find_all();
if((bool)$users->count())
{
	$i = 0;
	foreach ($users as $user)
	{
		$i++;
		echo '<tr>';
		echo '<td>'.$i.'</td>';
	    echo '<td>'.$user->link().'</td>';
	    echo '<td>'.$user->current_streak.'</td>';
	    echo '</tr>';
	}
}
?>
  </tbody>
</table>