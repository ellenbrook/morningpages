<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Ajax_Users extends Controller {
	
	private function post()
	{
		$user = ORM::factory('User', arr::get($_POST, 'id', false));
		if(!$user->loaded())
		{
			ajax::error(__('User not found. Has it been deleted in the meantime?'));
		}
		return $user;
	}
	
	private function get()
	{
		$user = ORM::factory('User', $this->request->param('id'));
		if(!$user->loaded())
		{
			ajax::error(__('User not found. Has it been deleted in the meantime?'));
		}
		return $user;
	}
	
	public function action_addrole()
	{
		$user = $this->post();
		$role = ORM::factory('Role', arr::get($_POST, 'role', false));
		if(!$role)
		{
			ajax::error(__('Role not found. Has it been deleted in the meantime?'));
		}
		try
		{
			$user->add('roles', $role);
			ajax::success('', array(
				'role' => $role->info()
			));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(
				':error' => $e->getMessage()
			)));
		}
	}
	
	public function action_removerole()
	{
		$user = $this->post();
		try
		{
			$user->remove('roles', arr::get($_POST, 'role'));
			ajax::success();
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(
				':error' => $e->getMessage()
			)));
		}
	}
	
	public function action_edit()
	{
		$user = $this->get();
		try
		{
			$user->update_user($_POST);
			ajax::success(__('Saved'));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('models');
			$errstr = array('<ul>');
			if((bool)count($errors))
			{
				foreach($errors as $error)
				{
					if(is_array($error))
					{
						foreach($error as $err)
						{
							$errstr[] = '<li>'.$err.'</li>';
						}
					}
					else
					{
						$errstr[] = '<li>'.$error.'</li>';
					}
				}
			}
			$errstr[] = '</li>';
			ajax::error(implode('',$errstr));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(
				':error' => $e->getMessage()
			)));
		}
	}
	
	public function action_create()
	{
		try
		{
			user::create($_POST);
			ajax::success(__('User created'),array(
				'redirect' => 'users'
			));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('models');
			$errstr = array('<ul>');
			if((bool)count($errors))
			{
				foreach($errors as $error)
				{
					if(is_array($error))
					{
						foreach($error as $err)
						{
							$errstr[] = '<li>'.$err.'</li>';
						}
					}
					else
					{
						$errstr[] = '<li>'.$error.'</li>';
					}
				}
			}
			$errstr[] = '</li>';
			ajax::error(implode('',$errstr));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(
				':error' => $e->getMessage()
			)));
		}
	}
	
	public function action_delete()
	{
		$user = $this->post();
		if(arr::get($_POST, 'action') == 'transfer')
		{
			$newowner = arr::get($_POST, 'newowner',3);
			$content = $user->contents->find_all();
			if((bool)$content->count())
			{
				foreach($content as $cont)
				{
					$cont->user_id = $newowner;
					$cont->save();
				}
			}
		}
		try
		{
			$user->delete();
			ajax::info(__('User deleted'));
		}
		catch(exception $e)
		{
			ajax::error(__('An uncaught error occurred: :error', array(
				':error' => $e->getMessage()
			)));
		}
	}
	
}

