<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Roles extends Controller {
	
	public function action_create()
	{
		$role = ORM::factory('Role');
		$role->name = arr::get($_POST, 'name', false);
		if(!$role->name)
		{
			ajax::error(__('Enter a name to create a new role'));
		}
		$role->description = arr::get($_POST, 'description', '');
		try
		{
			$role->save();
			ajax::success('', array('role' => $role->info()));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(':error' => $e->getMessage())));
		}
	}
	
	public function action_delete()
	{
		$role = ORM::factory('Role', arr::get($_POST, 'id'));
		if(!$role->loaded())
		{
			ajax::error(__('Role not found. Has it been deleted already?'));
		}
		try
		{
			$role->delete();
			ajax::info(__('Deleted'));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(':error' => $e->getMessage())));
		}
	}
	
}

