<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Token extends Model_Auth_User_Token {
	
	protected $_belongs_to = array(
		'user' => array()
	);
	
}
