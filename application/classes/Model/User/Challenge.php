<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Challenge extends ORM {
	
	protected $_belongs_to = array(
		'user' => array()
	);
	
}
