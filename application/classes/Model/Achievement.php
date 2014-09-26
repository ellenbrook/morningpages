<?php defined('SYSPATH') or die('No direct script access.');

class Model_Achievement extends ORM {
	
	protected $_has_many = array(
		'userachievements' => array()
	);
	
}
