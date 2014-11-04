<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron_Reminders extends Controller {
	
	/**
	 * 1. Run cron jobs every 15 minutes, say :10, :25, :40 and :55
	 * 2. Collect users where the reminder is due within the next 15 minutes
	 * 3. Make sure that the last_reminder was more than 24 hours - 15 mins ago
	 * 4. Send the mails
	 * 5. Set the last_sent timestamp to the user's preferred time. So if I want mails sent at 08:00 and the check runs and mails at 07:55,
	 * set the last_reminder timestamp to 08:00 (+5 minutes)
	 */
	
	public function action_index()
	{
		/*$start = microtime();
		//var_dump(date_default_timezone_get());
		$query = DB::query(Database::SELECT,
			'SELECT
				user.id,
				user.email,
				opt.id,
				opt.timezone_id,
				opt.reminder,
				opt.reminder_hour,
				opt.reminder_minute,
				opt.reminder_meridiem,
				opt.last_reminder,
				timezone.name
			FROM
				`users` AS user
			JOIN
				`user_options` AS opt
				ON user.id = opt.user_id
			JOIN
				`timezones` AS timezone
				ON timezone.id = opt.timezone_id
			WHERE
				opt.reminder = 1'
		);
		$results = $query->execute()->as_array();
		
		$datetimes = array();
		$timezones = array();
		
		$current_time = new DateTime();
		
		$datetime = new DateTime();
		
		if(is_array($results))
		{
			foreach($results as $result)
			{
				$result = (object)$result;
				if(!in_array($result->name, $timezones))
				{
					$timezones[$result->name] = new DateTimeZone($result->name);
				}
				$datetime = new DateTime();
				$datetime->setTimezone($timezones[$result->name]);
				//var_dump($datetime);
				$time = strtotime($datetime->date); // This is the current timestamp in the user's selected timezone
				var_dump($time);
			}
		}
		$end = microtime();
		echo '<p>Execution time: '.(($end - $start)/60).'</p>';
		var_dump('mem:',memory_get_usage());*/
	}
	
	public function action_send()
	{
		$query = DB::query(Database::SELECT,
		'SELECT
			user.id,
			user.email,
			opt.reminder,
			opt.last_reminder,
			opt.next_reminder
		FROM
			`users` AS user
		JOIN
			`user_options` AS opt
			ON
				user.id = opt.user_id
		WHERE
			opt.reminder = 1
		AND
			opt.next_reminder > '.strtotime('-5 minutes').'
		AND
			opt.next_reminder < '.strtotime('+5 minutes').';'
		);
		$results = $query->execute()->as_array();
		if(is_array($results))
		{
			foreach($results as $result)
			{
				$user = ORM::factory('User', arr::get($result,'id',''));
				if($user->loaded())
				{
					// Send the mail here..
					
					// Then:
					$option = $user->option;
					$option->last_reminder = time();
					$option->next_reminder = $user->get_next_reminder(true);
				}
			}
		}
	}
	
}
