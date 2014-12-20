<?php defined('SYSPATH') or die('No direct script access.');

class Model_Role extends ORM {
	
	protected $_has_many = array(
		'users' => array('through' => 'roles_users'),
		'messages' => array()
	);
	
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 1)),
				array('max_length', array(':value', 255)),
			)
		);
	}
	
	public function info()
	{
		return array(
			'id' => $this->id,
			'name' => __($this->name),
			'realname' => $this->name,
			'deleteable' => $this->deleteable(),
			'description' => __($this->description),
			'userCount' => $this->users->count_all()
		);
	}
	
	public function deleteable()
	{
		if($this->name == 'login'||$this->name=='admin'||$this->name=='developer')
		{
			return false;
		}
		return true;
	}
	
	public function delete()
	{
		if($this->name == 'login'||$this->name=='admin')
		{
			return false;
		}
		$users = $this->users->find_all();
		if((bool)$users->count())
		{
			foreach($users as $user)
			{
				$user->remove('roles',$this);
			}
		}
		return parent::delete();
	}
	
}
