<?php defined('SYSPATH') or die('No direct script access.');

class Model_Quote extends ORM {
	
	public function author()
	{
		if(!empty($this->author)) return $this->author;
		return 'Unknown';
	}
	
}
	