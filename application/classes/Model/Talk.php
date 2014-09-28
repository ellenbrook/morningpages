<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talk extends ORM {
	
	protected $_belongs_to = array(
		'talktag' => array(),
		'user' => array()
	);
	
	protected $_has_many = array(
		'replies' => array('model' => 'Talkreply')
	);
	
	public function labels()
	{
		return array(
			'title'		=> 'Title',
			'content'	=> 'Content'
		);
	}
	
	public function filters()
	{
		return array(
			'title' => array(
				array('Security::xss_clean', array(':value'))
			),
			'content' => array(
				array('Security::xss_clean', array(':value'))
			),
			'talktag_id' => array(
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
			'title' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 100)),
			),
			'content' => array(
				array('not_empty'),
				array('min_length', array(':value', 1)),
				array('max_length', array(':value', 1000))
			),
			'talktag_id' => array(
				array('not_empty')
			),
			'user_id' => array(
				array('not_empty')
			)
		);
	}
	
	public function url()
	{
		return 'talk/'.$this->talktag->slug.'/'.$this->id.'/'.$this->slug;
	}
	
	public function create(Validation $validation = NULL)
	{
		$this->slug = site::slugify($this->title);
		$this->created = time();
		return parent::create($validation);
	}
	
}
