<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Games extends Controller_Project {
	
	public function action_challenge()
	{
		seo::instance()->title("Take the 30 day writing challenge");
		seo::instance()->description("Take the 30 day Morning Page writing challenge, and improve yourself!");
	}
	
	public function action_walloffame()
	{
		seo::instance()->title("30 day writing challenge hall of fame!");
		seo::instance()->description("These users are the proud completers of the 30 day Morning Page challenge!");
	}
	
	public function action_leaderboard()
	{
		$active = DB::query(Database::SELECT, "
	        SELECT users.id, COUNT(talkreplies.user_id) as posts
	        FROM talkreplies
	        LEFT JOIN users ON users.id = talkreplies.user_id
	        GROUP BY talkreplies.user_id
	        ORDER BY posts DESC
	        LIMIT 10
	    ")->execute()->as_array();
		
		$streaks = ORM::factory('User')
			->order_by('current_streak', 'DESC')
			->limit(10)
			->find_all();
			
		$points = DB::query(Database::SELECT, "
			SELECT (SUM(page.points)+user.points) as points, page.user_id AS id FROM `pages` AS page
			JOIN `users` AS user ON user.id = page.user_id
			GROUP BY page.user_id
			ORDER BY points DESC
			LIMIT 20
		")->execute()->as_array();
		
		seo::instance()->title("Morning Pages leaderboard");
		seo::instance()->description("Morning Pages leaderboard");
		
		$this->bind('active', $active);
		$this->bind('streaks', $streaks);
		$this->bind('points', $points);
	}
	
}
