<?php defined('SYSPATH') or die('No direct script access.');

class Model_Dummytext extends ORM {
	
	public function text()
	{
		return nl2br($this->text);
	}
	
}
