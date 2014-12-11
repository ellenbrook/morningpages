<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Talkview extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'talk' => array()
	);
	
}
