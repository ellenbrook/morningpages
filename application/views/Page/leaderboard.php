<h2>Leaderboard</h2>

<?php

$users = ORM::factory('User')
	->order_by('current_streak', 'DESC')
	->limit(20)
	->find_all();
if((bool)$users->count())
{
	echo '<ol class="numbered">';
	foreach ($users as $user)
	{
		echo '<li>'.$user->link().' - '.$user->current_streak.'</li>';
	}
	echo '</ol>';
}

