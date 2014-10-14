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
	
	protected $_sorting = array(
		'created' => 'ASC'
	);
	
	public function filters()
	{
		return array(
			'talk_id' => array(
				array('Security::xss_clean', array(':value'))
			),
			'user_id' => array(
				array('Security::xss_clean', array(':value'))
			)
		);
	}
	
	public function markdown($text)
	{
		return markdown::instance()->convert($text);
	}
	
	public function content()
	{
		$content = $this->content;
		$content = $this->markdown($content);
		$content = Security::xss_clean($content);
		return $content;
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
