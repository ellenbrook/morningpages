<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Talkvote extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'talkreply' => array(),
		'talk' => array()
	);
	
}
