<?php defined('SYSPATH') or die('No direct script access.');
class Model_Option extends ORM {		protected $_belongs_to = array(		'optiongroup' => array()	);		public function info()	{		return array(			'id' => $this->id,			'key' => $this->key,			'type' => $this->type,			'title' => $this->title,			'description' => $this->description,			'editable' => $this->editable		);	}	}
	