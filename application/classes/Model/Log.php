<?php defined('SYSPATH') or die('No direct script access.');

class Model_Log extends ORM {
	
	public function get_action()
	{
		return $this -> action;
	}
	
	public function get_formatted_time()
	{
		$returner = '';
		$when = $this -> when;
		$now = time();
		$difference = $now - $when;
		$day = 60 * 60 * 24;
		if($difference > $day)
		{
			$days_ago = floor($difference / 24 / 60 / 60);
			$returner = $days_ago . ' dag' . (($days_ago == 1)?'':'e') . ' siden';
		}
		else
		{
			$returner = 'kl. ' . date('H.i', $when);
		}
		return $returner;
	}
	
}
