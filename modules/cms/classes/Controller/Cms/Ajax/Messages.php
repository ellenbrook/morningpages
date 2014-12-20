<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Messages extends Controller {
	
	public function action_toggleread()
	{
		$message = ORM::factory('Message', arr::get($_POST, 'id'));
		if(!$message->loaded())
		{
			ajax::error(__('The message wasn\'t found. Has it already been deleted?'));
		}
		$message->read = arr::get($_POST, 'read', '0');
		try
		{
			$message->save();
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(':error' => $e->getMessage())));
		}
	}
	
}

