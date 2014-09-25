<?php defined('SYSPATH') or die('No direct script access.');

class Model_Oauth extends ORM {
	
	protected $_belongs_to = array(
		'user' => array()
	);
	
}
