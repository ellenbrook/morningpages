<?php defined('SYSPATH') or die('No direct script access.');

class Model_Talk extends ORM {
	
	private $hotlimit = 10;
	
	protected $_belongs_to = array(
		'talktag' => array(),
		'user' => array()
	);
	
	protected $_has_many = array(
		'replies'	=> array('model' => 'Talkreply')
	);
	
	protected $_sorting = array(
		'last_reply' => 'DESC'
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
	
	public function deleted()
	{
		return $this->getop()->deleted();
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
			),
			'announcement' => array(
				array(array($this, 'canbeannouncement'), array('announcement',':value'))
			)
		);
	}
	
	public function canbeannouncement($field, $value)
	{
		return user::logged('admin');
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
	
	public function numreplies()
	{
		return $this->replies->where('op','!=',1)->count_all();
	}
	
	public function numpages()
	{
		$limit = Kohana::$config->load('talk')->get('pagination_limit');
		return ceil($this->numreplies()/$limit);
	}
	
	public function pagination($currentpage = 1)
	{
		$limit = Kohana::$config->load('talk')->get('pagination_limit');
		$numreplies = $this->numreplies();
		$numpages = $this->numpages();
		$html = '<div class="pagination">';
		$html .= '<ul>';
		$url = $this->url();
		if($currentpage > 1)
		{
			$prevnum = $currentpage - 1;
			$prevurl = $url;
			if($prevnum > 1)
			{
				$prevurl = $url.'?page='.$prevnum;
			}
			$html .= '<li class="page-prev"><a href="'.(URL::site($prevurl)).'">Prev</a></li>';
		}
		$html .= '<li>';
		$html .= '<ul>';
		for($i=1;$i<=$numpages;$i++)
		{
			$iurl = $url.'?page='.$i;
			if($i==1)
			{
				$iurl = $url;
			}
			$html .= '<li class="'.($currentpage==$i?'active':'').'"><a href="'.URL::site($iurl).'">'.$i.'</a></li>';
		}
		if($currentpage < $numpages)
		{
			$html .= '<li class="page-next"><a href="'.URL::site($url.'?page='.($currentpage+1)).'">Next</a></li>';
		}
		$html .= '</ul>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '</div>';
		return $html;
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
