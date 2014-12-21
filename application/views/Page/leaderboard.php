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

$users = ORM::factory('User')
	->order_by('current_streak', 'DESC')
	->limit(10)
	->find_all();
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
    $active = DB::query(Database::SELECT, "
        SELECT users.id, COUNT(talkreplies.user_id) as posts
        FROM talkreplies
        LEFT JOIN users ON users.id = talkreplies.user_id
        GROUP BY talkreplies.user_id
        LIMIT 10
    ")->execute()->as_array();

    foreach($active as $a)
    {
        $user = ORM::factory('user', arr::get($a, 'id'));
        if($user->loaded())
        {
           echo '<tr>';
           echo '<td>'.$user->username.'</td>';
           echo '<td>'.arr::get($a, 'posts', 0).'</td>';
           echo '</tr>';
        }
    }
?>
	  </tbody>
	</table>
	</div>