<?php defined('SYSPATH') or die('No direct script access.');

class Model_Userachievement extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'achievement' => array()
	);
	
}
