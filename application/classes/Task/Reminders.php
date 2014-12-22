<?php defined('SYSPATH') or die('No direct script access.');

class Task_Reminders extends Minion_Task {
	
	protected $_options = array();
	
	protected function _execute(array $params)
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
			$sent = array();
			foreach($results as $result)
			{
				$user = ORM::factory('User', arr::get($result,'id',''));
				if($user->loaded())
				{
					if(!in_array($user->email, $sent))
					{
						$sent[] = $user->email;
						// Send the mail here..
						$mail = mail::create('reminder')
							->to($user->email)
							->tokenize(array(
								'username' => $user->username,
								'writeurl' => 'http://morningpages.net/write',
								'link' => HTML::anchor('http://morningpages.net/write','It’s time to write your Morning Pages'),
								'contactlink' => HTML::anchor('http://morningpages.net/contact','contact us') )
							)
							->send();
						
						// Then:
						$option = $user->option;
						$option->last_reminder = time();
						$option->next_reminder = $user->get_next_reminder(true);
						$option->save();
					}
				}
			}
		}
	}
	
}
