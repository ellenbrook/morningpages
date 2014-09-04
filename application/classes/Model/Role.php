<?php defined('SYSPATH') or die('No direct script access.');

class Model_Role extends ORM {
	
	protected $_has_many = array(
		'users' => array('through' => 'roles_users')
	);
	
}
