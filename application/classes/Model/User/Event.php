<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Event extends ORM {
	
	protected $_belongs_to = array(
		'user' => array()
	);
	
	public function filters()
	{
		return array(
			'message' => array(
				array('Security::xss_clean', array(':value'))
			)
		);
	}
	
}
