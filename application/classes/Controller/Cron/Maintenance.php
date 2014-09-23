<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron_Maintenance extends Controller {
	
	public function action_index()
	{
		maintenance::reminders();
	}
	
}
