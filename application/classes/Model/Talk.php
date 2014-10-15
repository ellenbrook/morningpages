<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talk extends ORM {
	
	private $hotlimit = 3;
	
	protected $_belongs_to = array(
		'talktag' => array(),
		'user' => array()
	);
	
	protected $_has_many = array(
		'replies'	=> array('model' => 'Talkreply'),
		'votes'		=> array('model' => 'User_Talkvote')
	);
	
	protected $_sorting = array(
		'created' => 'DESC'
	);
	
	public function labels()
	{
		return array(
			'title'		=> 'Title',
			'content'	=> 'Content'
		);
	}
	
	public function votes()
	{
		return $this->votes->count_all() + 1;
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
	
	public function filters()
	{
		return array(
			'title' => array(
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
				array('not_empty'),
				array('digit'),
				array(array($this, 'validtagid'), array('talktag_id', ':value'))
			),
			'user_id' => array(
				array('not_empty')
			)
		);
	}
	
	public function validtagid($field, $value)
	{
		return (bool)ORM::factory('Talktag',(int)$value)->loaded();
	}
	
	public function username()
	{
		return $this->user->username();
	}
	
	public function hot()
	{
		return $this->replies->count_all()>$this->hotlimit;
	}
	
	public function excerpt()
	{
		$content = $this->content;
		$content = Security::xss_clean($content);
		$content = strip_tags($content);
		return substr($content, 0, 200).(strlen($content)>200?'&hellip;':'');
	}
	
	public function url()
	{
		return $this->talktag->url().'/'.$this->id.'/'.$this->slug;
	}
	
	public function create(Validation $validation = NULL)
	{
		$this->slug = site::slugify($this->title);
		$this->created = time();
		return parent::create($validation);
	}
	
}
