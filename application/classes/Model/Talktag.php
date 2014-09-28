<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talktag extends ORM {
	
	protected $_has_many = array(
		'talks' => array()
	);
	
}
