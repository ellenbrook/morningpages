<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Talksubscription extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'talk' => array()
	);
	
}
