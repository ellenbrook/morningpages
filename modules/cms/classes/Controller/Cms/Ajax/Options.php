<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Options extends Controller {
	
	public function action_index()
	{
		if($_POST)
		{
			try
			{
				foreach($_POST['option'] as $option_id => $value)
				{
					$option = ORM::factory('Option', $option_id);
					$option -> value = $value;
					$option -> save();
				}
				if(arr::get($_FILES, 'option',false)) foreach($_FILES['option']['name'] as $key => $file)
				{
					$ext = $_FILES['option']['name'][$key];
					$ext = explode('.', $ext);
					$ext = end($ext);
					$filename = upload::save(array(
						'name' => $_FILES['option']['name'][$key],
						'type' => $_FILES['option']['type'][$key],
						'tmp_name' => $_FILES['option']['tmp_name'][$key],
						'error' => $_FILES['option']['error'][$key],
						'size' => $_FILES['option']['size'][$key],
					), 'option-' . $key . '.' . $ext, 'media/uploads');
					$option = ORM::factory('Option', $key);
					$option -> value = 'option-' . $key . '.' . $ext;
					$option->save();
				}
				ajax::success(__('Settings saved'));
			}
			catch(ORM_Validation_Exception $e)
			{
				ajax::error(__('An error occured and the settings couldn\'t be saved: :error', array(
					':error' => $e->getMessage()
				)));
			}
		}
	}
	
	public function action_delete()
	{
		$option = ORM::factory('Option', arr::get($_POST, 'id',''));
		if(!$option->loaded())
		{
			ajax::error('Option not found. Has it already been deleted?');
		}
		try
		{
			$option->delete();
			ajax::success('Deleted');
		}
		catch(exception $e)
		{
			ajax::error('An error occurred and the option could not be deleted. The site said: '.$e->getMessage());
		}
	}
	
	public function action_add()
	{
		$title = arr::get($_POST, 'title',false);
		if(!$title || empty($title))
		{
			ajax::error('A title is required');
		}
		$option = ORM::factory('Option');
		$option->optiongroup_id = arr::get($_POST, 'group');
		$option->title = $title;
		$option->key = arr::get($_POST, 'key','');
		$option->type = arr::get($_POST, 'type','text');
		$option->description = arr::get($_POST, 'description','');
		$option->editable = arr::get($_POST, 'editable', '1');
		try
		{
			$option->save();
			ajax::success('Option added', array(
				'option' => $option->info()
			));
		}
		catch(exception $e)
		{
			ajax::error('Something went wrong: '.$e->getMessage());
		}
	}
	
}

