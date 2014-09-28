<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talkreply extends ORM {
	
	protected $_belongs_to = array(
		'talk' => array(),
		'user' => array()
	);
	
	public function labels()
	{
		return array(
			'content'	=> 'Content'
		);
	}
	
	public function filters()
	{
		return array(
			'content' => array(
				array('Security::xss_clean', array(':value'))
			),
			'talk_id' => array(
				array('Security::xss_clean', array(':value'))
			),
			'user_id' => array(
				array('Security::xss_clean', array(':value'))
			)
		);
	}
	
	public function rules()
	{
		return array(
			'content' => array(
				array('not_empty'),
				array('min_length', array(':value', 1)),
				array('max_length', array(':value', 1000))
			),
			'talk_id' => array(
				array('not_empty')
			),
			'user_id' => array(
				array('not_empty')
			)
		);
	}
	
	public function create(Validation $validation = NULL)
	{
		$this->created = time();
		return parent::create($validation);
	}
	
}
