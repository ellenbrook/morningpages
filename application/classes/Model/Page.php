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
				array('Security::xss_clean', array(':value')),
				array(array($this, 'encrypt_content'), array(':value')),
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
		$numwords = str_word_count(strip_tags($value));
		return $numwords >= 1;
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
    
    public function stopwords()
    {
        //return array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
        //return array(' a ',' about ',' above ',' above ',' across ',' after ',' afterwards ',' again ',' against ',' all ',' almost ',' alone ',' along ',' already ',' also ',' although ',' always ',' am ',' among ',' amongst ',' amoungst ',' amount ',' an ',' and ',' another ',' any ',' anyhow ',' anyone ',' anything ',' anyway ',' anywhere ',' are ',' around ',' as ',' at ',' back ',' be ',' became ',' because ',' become ',' becomes ',' becoming ',' been ',' before ',' beforehand ',' behind ',' being ',' below ',' beside ',' besides ',' between ',' beyond ',' bill ',' both ',' bottom ',' but ',' by ',' call ',' can ',' cannot ',' cant ',' co ',' con ',' could ',' couldnt ',' cry ',' de ',' describe ',' detail ',' do ',' done ',' down ',' due ',' during ',' each ',' eg ',' eight ',' either ',' eleven ',' else ',' elsewhere ',' empty ',' enough ',' etc ',' even ',' ever ',' every ',' everyone ',' everything ',' everywhere ',' except ',' few ',' fifteen ',' fify ',' fill ',' find ',' fire ',' first ',' five ',' for ',' former ',' formerly ',' forty ',' found ',' four ',' from ',' front ',' full ',' further ',' get ',' give ',' go ',' had ',' has ',' hasnt ',' have ',' he ',' hence ',' her ',' here ',' hereafter ',' hereby ',' herein ',' hereupon ',' hers ',' herself ',' him ',' himself ',' his ',' how ',' however ',' hundred ',' ie ',' if ',' in ',' inc ',' indeed ',' interest ',' into ',' is ',' it ',' its ',' itself ',' keep ',' last ',' latter ',' latterly ',' least ',' less ',' ltd ',' made ',' many ',' may ',' me ',' meanwhile ',' might ',' mill ',' mine ',' more ',' moreover ',' most ',' mostly ',' move ',' much ',' must ',' my ',' myself ',' name ',' namely ',' neither ',' never ',' nevertheless ',' next ',' nine ',' no ',' nobody ',' none ',' noone ',' nor ',' not ',' nothing ',' now ',' nowhere ',' of ',' off ',' often ',' on ',' once ',' one ',' only ',' onto ',' or ',' other ',' others ',' otherwise ',' our ',' ours ',' ourselves ',' out ',' over ',' own ',' part ',' per ',' perhaps ',' please ',' put ',' rather ',' re ',' same ',' see ',' seem ',' seemed ',' seeming ',' seems ',' serious ',' several ',' she ',' should ',' show ',' side ',' since ',' sincere ',' six ',' sixty ',' so ',' some ',' somehow ',' someone ',' something ',' sometime ',' sometimes ',' somewhere ',' still ',' such ',' system ',' take ',' ten ',' than ',' that ',' the ',' their ',' them ',' themselves ',' then ',' thence ',' there ',' thereafter ',' thereby ',' therefore ',' therein ',' thereupon ',' these ',' they ',' thickv ',' thin ',' third ',' this ',' those ',' though ',' three ',' through ',' throughout ',' thru ',' thus ',' to ',' together ',' too ',' top ',' toward ',' towards ',' twelve ',' twenty ',' two ',' un ',' under ',' until ',' up ',' upon ',' us ',' very ',' via ',' was ',' we ',' well ',' were ',' what ',' whatever ',' when ',' whence ',' whenever ',' where ',' whereafter ',' whereas ',' whereby ',' wherein ',' whereupon ',' wherever ',' whether ',' which ',' while ',' whither ',' who ',' whoever ',' whole ',' whom ',' whose ',' why ',' will ',' with ',' within ',' without ',' would ',' yet ',' you ',' your ',' yours ',' yourself ',' yourselves ',' the ');
        return array(' a ',' able ',' about ',' across ',' after ',' all ',' almost ',' also ',' am ',' among ',' an ',' and ',' any ',' are ',' as ',' at ',' be ',' because ',' been ',' but ',' by ',' can ',' cannot ',' could ',' dear ',' did ',' do ',' does ',' either ',' else ',' ever ',' every ',' for ',' from ',' get ',' got ',' had ',' has ',' have ',' he ',' her ',' hers ',' him ',' his ',' how ',' however ',' i ',' if ',' in ',' into ',' is ',' it ',' its ',' just ',' least ',' let ',' like ',' likely ',' may ',' me ',' might ',' most ',' must ',' my ',' neither ',' no ',' nor ',' not ',' of ',' off ',' often ',' on ',' only ',' or ',' other ',' our ',' own ',' rather ',' said ',' say ',' says ',' she ',' should ',' since ',' so ',' some ',' than ',' that ',' the ',' their ',' them ',' then ',' there ',' these ',' they ',' this ',' tis ',' to ',' too ',' twas ',' us ',' wants ',' was ',' we ',' were ',' what ',' when ',' where ',' which ',' while ',' who ',' whom ',' why ',' will ',' with ',' would ',' yet ',' you ',' your ');
    }
	
	public function wordcloud($wordlimit = 30)
	{
		$content = $this->content();
		$content = strtolower($content);
		$content = strip_tags($content);
		
		$remove_signs = array('!','?',',','.', ':','-','"');
		$remove_words = $this->stopwords();
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
		$classes = array('orange','red','brown','green');
		foreach($words as $word => $count)
		{
			$size = floor(75 * ($count / $max));
			if($size < 10)
			{
				$size = 10;
			}
			$html .= '<span style="font-size:' . $size . 'px;" class="'.$classes[rand(0,count($classes)-1)].'">'.$word.'</span> ';
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
		$content = strip_tags($this->content);
        $replace = array("\r\n", "\n", "\r", "\t", '.', ',', ';', "'", '@');
        $content = str_replace($replace, ' ', $content);
        $count = str_word_count($content);
		return $count;
	}
	
	public function create(Validation $validation = NULL)
	{
		$user = user::get();
		$this->user_id = $user->id;
		$this->created = time();
		$this->day = site::today_slug();
		
		/*$user->current_streak += 1;
		if($user->current_streak > $user->longest_streak)
		{
			$user->longest_streak = $user->current_streak;
		}
		$user->save();*/
		
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
