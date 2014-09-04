<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends ORM {
	
	protected $_belongs_to = array(
		'user' => array()
	);
	
	protected $_sorting = array(
		'created' => 'DESC'
	);
	
	public function filters()
	{
		return array(
			'content' => array(
				array('trim'),
				//array(array($this, 'add_paragraphs'), array(':value')),
				array(array($this, 'encrypt_content'), array(':value'))
			)
		);
	}
	
	public function markdown($text)
	{
		return Markdown::instance()->transform($text);
	}
	
	public function encrypt_content($content)
	{
		if($content == '') return $content;
		$enc = Encrypt::instance();
		return $enc->encode($content);
	}
	
	public function open()
	{
		return date('Ymd') == date('Ymd', $this->created);
	}
	
	public function content()
	{
		$enc = Encrypt::instance();
		$cont = $enc->decode($this->content);
		$cont = $this->markdown($cont);
		return $cont;
	}
	
	public function textarea_content()
	{
		$cont = self::content();
		$cont = str_ireplace(array('<br />','<br>','<br/>'), "\r", $cont);
		$cont = str_ireplace(array('<p>','</p>'),'',$cont);
		return trim($cont);
	}
	
	public function add_paragraphs($content)
	{
		$content = Security::xss_clean($content);
		/*$content = '<p>'.preg_replace(
			array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
			array("</p>\n<p>", "</p>\n<p>", '$1<br>$2'),
			trim($content)
		).'</p>';*/
		$content = nl2br($content);
		
		return $content;
	}
	
	public function verify_word_count($field, $value)
	{
		$numwords = str_word_count($value);
		return $numwords >= 750;
	}
	
	public function date()
	{
		if(!$this->day)
		{
			$this->day = site::today_slug();
		}
		list($month, $day, $year) = explode('-',$this->day);
		$daystamp = mktime(0,0,0,$month,$day,$year);
		$today = mktime(0,0,0,date('n'),date('j'),date('Y'));
		$today = site::today_slug();
		if($today == $this->day)
		{
			return 'Today';
		}
		return date('F d, Y', $daystamp);
	}
	
	public function wordcloud($wordlimit = 30)
	{
		$content = $this->content();
		$content = strtolower($content);
		$content = strip_tags($content);
		
		$remove_signs = array('!','?',',','.', ':');
		$remove_words = array(' and ',' a ',' one',' as ',' to ', ' for ', ' on ', ' the ', ' to ', ' it ', ' from ', ' by ', ' then ', ' i ', ' in ', ' that ', ' with ', ' where ', ' out ', ' we ', ' of ', ' is ', ' have ', ' am ', ' going ', ' things ', ' how ', ' be ', ' but ', ' will ', ' just ', ' even ' , ' the ', ' about ', ' it ', ' if ', ' should ', ' definitely ');
		$desc = str_replace($remove_signs,'',$content);
		$desc = str_replace($remove_words,' ',$desc);
		$descwords = explode(' ', $desc);
		$words = array();
		foreach($descwords as $w)
		{
			if(in_array($w, array_keys($words)) && isset($words[$w]))
			{
				$words[$w]++;
			}
			else
			{
				$words[$w] = 1;
			}
		}
		$html = '';
		$max = max($words);
		uasort($words, create_function('$a, $b', 'if($a == $b){return 0;}return ($a > $b) ? -1 : 1;'));
		$words = array_slice($words, 0, $wordlimit, true);
		$words = $this->shuffle_assoc($words);
		foreach($words as $word => $count)
		{
			$size = floor(75 * ($count / $max));
			if($size < 10)
			{
				$size = 10;
			}
			$html .= '<span style="font-size:' . $size . 'px;">'.$word.'</span> ';
		}
		return $html;
	}
	
	private function shuffle_assoc($list)
	{ 
		if (!is_array($list)) return $list;
		 
		$keys = array_keys($list); 
		shuffle($keys); 
		$random = array(); 
		foreach ($keys as $key)
		{ 
			$random[$key] = $list[$key]; 
		}
		return $random; 
	}
	
	public function wordcount()
	{
		$content = explode(' ', $this->content());
		$count = count($content);
		if(empty($this->content)) $count = 0;
		return $count;
	}
	
	public function create(Validation $validation = NULL)
	{
		$user = user::get();
		$this->user_id = $user->id;
		$this->created = time();
		$this->day = site::today_slug();
		
		$user->current_streak += 1;
		if($user->current_streak > $user->longest_streak)
		{
			$user->longest_streak = $user->current_streak;
		}
		$user->save();
		
		return parent::create($validation);
	}
	
	public function autosave(Validation $validation = NULL)
	{
		if(!$this->loaded())
		{
			return parent::save($validation);
		}
		return parent::update($validation);
	}
	
	public function get_autosave()
	{
		// Cleanup - old autosaves
		$old_autosaves = ORM::factory('Page')
			->where('user_id','=',$this->user_id)
			->where('parent','!=',$this->id)
			->where('type','=','autosave')
			->find_all();
		if((bool)$old_autosaves->count())
		{
			foreach($old_autosaves as $old)
			{
				$old->delete();
			}
		}
		// Current autosave
		$autosave = ORM::factory('Page')
			->where('user_id','=',$this->user_id)
			->where('parent','=',$this->id)
			->where('type','=','autosave')
			->find();
		if($autosave->loaded())
		{
			if($autosave->content() == '')
			{
				$autosave->delete();
				return false;
			}
			return $autosave;
		}
		return false;
	}
	
	public function update(Validation $validation = NULL)
	{
		if(!$validation)
		{
			$validation = Validation::factory(array(
				'content' => $this->content,
				'user_id' => $this->user_id
			));
		}
		$validation
			->rule('user_id', 'not_empty')
			->rule('content', 'not_empty')
			->rule('content', array($this, 'verify_word_count'), array('content',':value'))
			->rule('content', 'max_length', array(':value', 100000));
		
		return parent::update($validation);
	}
	
	public function delete()
	{
		$autosave = ORM::factory('Page')->where('type','=','autosave')->where('parent','=',$this->id)->find();
		if($autosave->loaded())
		{
			$autosave->delete();
		}
		return parent::delete();
	}
	
}
