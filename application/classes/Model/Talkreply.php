<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talkreply extends ORM {
	
	protected $_belongs_to = array(
		'talk' => array(),
		'user' => array(),
		'replyto' => array('model' => 'Talkreply', 'foreign_key' => 'replyto_id')
	);
	
	protected $_has_many = array(
		'votes'		=> array('model' => 'User_Talkvote'),
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
			),
			'replyto_id' => array(
				array('Security::xss_clean', array(':value'))
			)
		);
	}
	
	public function markdown($text)
	{
		return markdown::instance()->convert($text);
	}
	
	public function votes()
	{
		return ORM::factory('User_Talkvote')
			->where('object_id','=',$this->id)
			->where('type','=','talkreply')
			->count_all();
	}
	
	public function content()
	{
		$content = $this->content;
		$content = $this->markdown($content);
		$content = Security::xss_clean($content);
		return $content;
	}
	
	public function quote()
	{
		$content = $this->content;
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
		$content = $this->content;
		$content = str_replace("\n", "\r\n", $content);
		//$content = Security::xss_clean($content);
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
			'replyto_id' => array(
				array('digit')
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
