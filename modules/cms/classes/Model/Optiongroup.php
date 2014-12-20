<?php defined('SYSPATH') or die('No direct script access.');
class Model_Optiongroup extends ORM {		protected $_has_many = array(		'options' => array()	);		public function info()	{		return array(			'id' => $this->id,			'display' => $this->display,			'slug' => $this->slug		);	}	}
	