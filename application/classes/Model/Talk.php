<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talk extends ORM {
	
	private $hotlimit = 3;
	
	protected $_belongs_to = array(
		'talktag' => array(),
		'user' => array()
	);
	
	protected $_has_many = array(
		'replies'	=> array('model' => 'Talkreply')
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
		return ORM::factory('User_Talkvote')
			->where('object_id','=',$this->opid())
			->where('type','=','talkreply')
			->count_all();
	}
	
	public function markdown($text)
	{
		return markdown::instance()->convert($text);
	}
	
	public function getop()
	{
		return $this->replies->where('op','=',1)->find();
	}
	
	public function opid()
	{
		return $this->getop()->id;
	}
	
	public function content()
	{
		$op = $this->getop();
		$content = '';
		if($op->loaded())
		{
			$content = $op->content();
		}
		return $content;
	}
	
	public function rawcontent()
	{
		$op = $this->replies->where('op','=',1)->find();
		$content = '';
		if($op->loaded())
		{
			$content = $op->content;
		}
		return $content;
	}
	
	public function quote()
	{
		$content = $this->rawcontent();
		$content = Security::xss_clean($content);
		$content = str_replace("\r\n", "\n", $content);
		$newcontent = '';
		$lines = explode("\n", $content);
		if(is_array($lines))
		{
			foreach($lines as $line)
			{
				$newcontent .= '> '.$line."\r\n";
			}
		}
		else
		{
			// Empty?
			$newcontent = $content;
		}
		return $newcontent;
	}
	
	public function raw()
	{
		$content = $this->rawcontent();
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
		return $this->user->username(true);
	}
	
	public function hot()
	{
		return $this->replies->count_all()>$this->hotlimit;
	}
	
	public function excerpt()
	{
		$content = $this->content();
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
