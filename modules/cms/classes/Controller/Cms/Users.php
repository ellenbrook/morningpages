<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cms_Users extends Controller {
	
	public function action_index()
	{
		$limit = 20;
		$type = arr::get($_GET, 'role', false);
		$usercount = ORM::factory('User')->count_all();
		$numpages = ceil($usercount / $limit);
		$page = arr::get($_GET, 'page', 1);
		if($page > $numpages)
		{
			$page = $numpages;
		}
		$page = $page-1;
		if($page < 0)
		{
			$page = 0;
		}
		$offset = $page*$limit;
		
		$users = ORM::factory('User');
		$counter = ORM::factory('User');
		$currentrole = false;
		if($type)
		{
			$currentrole = ORM::factory('Role')
				->where('name','=',$type)
				->find();
			$users = $currentrole->users;
			$counter = $currentrole->users;
		}
		$numresults = $counter->count_all();
		$users = $users
			->offset($offset)
			->limit($limit)
			->find_all();
		$userarr = array();
		if((bool)$users->count())
		{
			foreach($users as $user)
			{
				$userarr[] = $user->info();
			}
		}
		
		$roles = ORM::factory('Role')->find_all();
		$rolesarr = array();
		if((bool)$roles->count())
		{
			foreach($roles as $role)
			{
				$rolesarr[] = $role->info();
			}
		}
		
		$view = View::factory('Cms/Users/index',array(
		 'roles' => $roles
		));
		$view->pagination = cms::pagination($numresults, '#/users', $limit);
		reply::ok($view, 'users', array(
			'viewModel' => 'viewModels/Users/index',
			'userCount' => $usercount,
			'limit' => $limit,
			'users' => $userarr,
			'page' => $page,
			'roles' => $rolesarr,
			'role' => $type
		));
	}
	
	public function action_roles()
	{
		$roles = ORM::factory('Role')->find_all();
		$rolesarr = array();
		if((bool)$roles->count())
		{
			foreach($roles as $role)
			{
				$rolesarr[] = $role->info();
			}
		}
		
		$view = View::factory('Cms/Users/roles');
		reply::ok($view, 'users', array(
			'viewModel' => 'viewModels/Users/roles',
			'roles' => $rolesarr,
		));
	}
	
	public function action_edit()
	{
		$user = ORM::factory('User', $this->request->param('id'));
		if(!$user->loaded())
		{
			reply::redirect('#/users', __('User not found. Has it already been deleted?'));
		}
		
		$roles = ORM::factory('Role')->find_all();
		$rolesarr = array();
		if((bool)$roles->count())
		{
			foreach($roles as $role)
			{
				$rolesarr[] = $role->info();
			}
		}
		
		$view = View::factory('Cms/Users/edit',array(
			'user' => $user,
			'roles' => $roles
		));
		reply::ok($view, 'users', array(
			'viewModel' => 'viewModels/Users/edit',
			'user' => $user->info(),
			'roles' => $rolesarr
		));
	}
	
	public function action_new()
	{
		$view = View::factory('Cms/Users/new');
		reply::ok($view, 'users', array(
			'viewModel' => 'viewModels/Users/new'
		));
	}
	
}
