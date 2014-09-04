<?php defined('SYSPATH') or die('No direct script access.');

class Model_Error extends ORM {
	
	public function get_url()
	{
		return $this->url;
	}
	
	public function get_referrer()
	{
		return $this->referrer;
	}
	
	public function get_when()
	{
		return $this->when;
	}
	
}
