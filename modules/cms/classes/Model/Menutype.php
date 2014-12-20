<?php defined('SYSPATH') or die('No direct script access.');

class Model_Menutype extends orm
{
	
	protected $_has_one = array(
		'menu' => array()
	);
	
	public function get_title()
	{
		return $this -> title;
	}
	
}