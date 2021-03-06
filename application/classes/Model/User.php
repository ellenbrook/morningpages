<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {
	
	protected $_has_many = array(
		'tokens'			=> array('model' => 'User_Token'),
		'roles'    			=> array('through' => 'roles_users'),
		'pages'				=> array(),
		'oauths'			=> array(),
		'userachievements'	=> array(),
		'votes'				=> array('model' => 'User_Talkvote'),
		'talkviews'			=> array('model' => 'User_Talkview'),
		'dashboards'		=> array(),
		'contents'			=> array(),
		'messages'			=> array(),
		'talksubscriptions'	=> array('model' => 'User_Talksubscription'),
		'talkreplies'		=> array(),
		'events'			=> array('model' => 'User_Event')
	);
	
	protected $_belongs_to = array(
		'theme' => array()
	);
	
	protected $_has_one = array(
		'option' => array('model' => 'User_Option'),
		'challenge' => array('model' => 'User_Challenge')
	);
	
	protected $_do_validation = true;
	
	public function fail_challenge()
	{
		$this->add_event('Failed the 30 day challenge!');
		$this->challenge->delete();
	}
	
	public function add_event($message)
	{
		$event = ORM::factory('User_Event');
		$event->user_id = $this->id;
		$event->message = $message;
		$event->created = $this->timestamp();
		$event->save();
		return $event;
	}
	
	public function info()
	{
		$roles = $this->roles->find_all();
		$rolesarr = array();
		if((bool)$roles->count())
		{
			foreach($roles as $role)
			{
				$rolesarr[] = $role->info();
			}
		}
		$doingChallenge = $this->doing_challenge();
		$challengeProgress = 0;
		if($doingChallenge)
		{
			$challengeProgress = $this->challenge->progress;
		}
		return array(
			'id' => $this->id,
			'email' => $this->email,
			'name' => $this->username,
			'slug' => $this->slug,
			'bio' => $this->bio,
			'website' => $this->website,
			'longest_streak' => $this->longest_streak,
			'current_streak' => $this->current_streak,
			'most_words' => $this->most_words,
			'all_time_words' => $this->all_time_words,
			'points' => $this->points(),
			'logins' => $this->logins,
			'gravatar' => array(
				'mini' => $this->gravatar(32),
				'medium' => $this->gravatar(145)
			),
			'last_login' => array(
				'timestamp' => $this->last_login,
				'formatted' => ($this->last_login>0?date('d-m-Y H:i', $this->last_login):__('Never'))
			),
			'delete' => 0,
			'created' => array(
				'timestamp' => $this->created,
				'formatted' => date('d-m-Y H:i', $this->created)
			),
			'roles' => $rolesarr,
			'doingChallenge' => $doingChallenge,
			'challengeProgress' => $challengeProgress
		);
	}
	
	public function points()
	{
		$points = DB::select(array(DB::expr('SUM(`points`)'),'points'))
			->from('pages')
			->where('type','=','page')
			->where('user_id', '=', $this->id)
			->execute()
			->get('points');
		return $points+$this->points;
	}
	
	public function doing_challenge()
	{
		return $this->challenge->loaded();
	}
	
	public function validation_required($required = true)
	{
		if($required)
		{
			return $this->_do_validation;
		}
		$this->_do_validation = (bool)$required;
		return $this;
	}
	
	public function time()
	{
		return new DateTime(null, new DateTimeZone($this->option->timezone->name));
	}
	
	public function timestamp()
	{
		$time = $this->time();
		return strtotime($time->format('Y-m-d h:i:s'));
	}
	
	public function get_next_reminder($tomorrow = false)
	{
		$str = date('n').'/'.date('j').'/'.date('Y').' '.$this->option('reminder_hour').':'.sprintf('%02s',$this->option('reminder_minute')).' '.$this->option('reminder_meridiem');
		$timestamp = strtotime($str);
		$user_datestr = date('m/d/Y h:i A',$timestamp);
		$user_timezone = $this->option->timezone->name;
		
		$user_date = new DateTime($user_datestr, new DateTimeZone($user_timezone));
		if($tomorrow)
		{
			$user_date->modify('+1 day');
		}
		$user_date->setTimezone(new DateTimeZone('America/New_York'));
		
		$server_date = $user_date->format('m/d/Y h:i A');
		$next = strtotime($server_date);
		
		return $next;
	}
	
	public function wrote_today()
	{
		$slug = $this->today_slug();
		$page = ORM::factory('Page')
			->where('user_id','=',$this->id)
			->where('type','=','page')
			->where('day','=',$slug)
			->where('wordcount','>',750)
			->find();
		return $page->loaded();
	}
	
	public function today_slug()
	{
		return site::day_slug($this->timestamp());
	}
	
	public function votedon($id, $type = 'talkreply')
	{
		return (bool)$this->votes
			->where('type','=',$type)
			->where('object_id','=',$id)
			->count_all();
	}
	
	public function gravatar($size = 150)
	{
		$email = $this->email;
		$email = trim($email);
		$email = strtolower($email);
		$email = md5($email);
		return 'http://gravatar.com/avatar/'.$email.'?s='.$size.'&rating=pg';
	}
	
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = time();

			// Save the user
			$this->validation_required(false)->save();
		}
	}
	
	public function email_available(Validation $validation, $field)
	{
		if ($this->unique_key_exists($validation[$field], 'email'))
		{
			$validation->error($field, 'email_available', array($validation[$field]));
		}
	}
	
	public function username_available(Validation $validation, $field)
	{
		if(in_array($validation[$field],user::reservednames()))
		{
			$validation->error($field, 'username_available', array($validation[$field]));
		}
		if ($this->unique_key_exists($validation[$field], 'username'))
		{
			$validation->error($field, 'username_available', array($validation[$field]));
		}
	}
	
	public function send_password_email($password)
	{
		try
		{
			$mail = mail::create('userautocreated')
				->to($this->email)
				->tokenise(array(
					'username' => $this->username,
					'password' => $password
				))
				->send();
			return true;
		}
		catch(exception $e)
		{
			log::instance('Couldnt send user autogenerated password', $e);
		}
		return false;
	}
	
	public function delete()
	{
		foreach($this -> tokens -> find_all() as $token)
		{
			$token -> delete();
		}
		foreach($this -> roles -> find_all() as $role)
		{
			$this -> remove('roles', $role -> id);
		}
		$pages = $this->pages->find_all();
		if((bool)$pages->count())
		{
			foreach($pages as $page)
			{
				$page->delete();
			}
		}
		$oauths = $this->oauths->find_all();
		if((bool)$oauths->count())
		{
			foreach($oauths as $oauth)
			{
				$oauth->delete();
			}
		}
		
		$challenge = ORM::factory('User_Challenge')
			->where('user_id', '=', $this->id)
			->find();
		if($challenge->loaded())
		{
			$challenge->delete();
		}
		
		
		return parent::delete();
	}
	
	public function rules()
	{
		if($this->validation_required())
		{
			return array(
				'username' => array(
					array('not_empty'),
					array('min_length', array(':value', 2)),
					array('max_length', array(':value', 30)),
					array(array($this, 'unique'), array('username', ':value'))
				),
				'password' => array(
					array('not_empty'),
					array('min_length', array(':value', 5)),
					array('max_length', array(':value', 64))
				),
				'bio' => array(
					array('max_length', array(':value', 1000)),
				),
				'website' => array(
					array('max_length', array(':value', 100)),
				),
				'email' => array(
					array('email'),
					array('not_empty'),
					array('min_length', array(':value', 4)),
					array('max_length', array(':value', 127)),
					array(array($this, 'unique'), array('email', ':value'))
				),
				'theme' => array(
					array(array($this, 'verify_available_theme'), array('theme',':value'))
				)
			);
		}
		else
		{
			return array(
				'username' => array(),
				'password' => array(),
				'email' => array(),
				'bio' => array(),
				'website' => array(),
				'theme' => array()
			);
		}
		return array();
	}
	
	public function verify_available_theme($field, $value)
	{
		if($value === 0)
		{
			// Standard theme
			return true;
		}
		return (bool)ORM::factory('Theme',$value)->loaded();
	}
	
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 5))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}
	
	public function get_last_login()
	{
		return date('d/m, Y', $this -> last_login) . ' kl. ' . date('H:i:s', $this->last_login);
	}
	
	public function labels()
	{
		return array(
			'email'		=> 'E-mail',
			'password'	=> 'Password',
			'username'	=> 'Username',
			'bio'		=> 'Bio',
			'website'	=> 'Website',
			'theme'		=> 'Your theme'
		);
	}
	
	public function filters()
	{
		if($this->validation_required())
		{
			return array(
				'password' => array(
					array(array(Auth_ORM::instance(), 'hash'))
				),
				'username' => array(
					array('Security::xss_clean', array(':value'))
				),
				'email' => array(
					array('Security::xss_clean', array(':value'))
				),
				'bio' => array(
					array('Security::xss_clean', array(':value'))
				),
				'website' => array(
					array('Security::xss_clean', array(':value'))
				),
				'theme' => array(
					array('Security::xss_clean', array(':value'))
				)
			);
		}
		else
		{
			return array(
				'username' => array(
					array('Security::xss_clean', array(':value'))
				),
				'bio' => array(
					array('Security::xss_clean', array(':value'))
				),
				'website' => array(
					array('Security::xss_clean', array(':value'))
				),
			);
		}
	}
	
	public function has_role($name)
	{
		foreach($this->roles->find_all() as $role)
		{
			if($role->name == $name)
			{
				return true;
			}
		}
		return false;
	}
	
	public function set_roles($roleids = false)
	{
		$this->remove('roles');
		if($roleids)
		{
			$this->add('roles', $roleids);
		}
	}
	
	public function create(Validation $val = NULL)
	{
		$result = parent::create($val);
		
		$slug = site::slugify($this->username);
		$orgslug = $slug;
		$existing = ORM::factory('User')->where('slug','=',$slug)->find();
		$i = 2;
		while($existing->loaded())
		{
			$slug = $orgslug.'-'.$i;
			$i++;
		}
		$this->slug = $slug;
		$this->update();
		
		$options = ORM::factory('User_Option');
		$options->user_id = $this->id;
		$options->save();
	}
	
	public function save(validation $val = null)
	{
		if($this->created == 0)
		{
			$this->created = time();
		}
		return parent::save($val);
	}
	
	public function option($name)
	{
		return $this->option->$name;
	}
	
	public function get_roles()
	{
		return $this->roles->find_all();
	}
	
	public function get_url()
	{
		return user::url('konto/' . $this->id);
	}
	
	public function update_password($data)
	{
		$data = Validation::factory($data)
			-> rule('password', 'not_empty')
			-> rule('password', 'min_length', array(':value', 4))
			-> rule('password', 'max_length', array(':value', 64))
			-> label('password', 'Password')
			
			-> rule('password_confirm', 'not_empty')
			-> rule('password_confirm', 'matches', array(':validation', ':field', 'password'))
			-> label('password_confirm', 'Password confirm');
			
		if(!$data->check())
		{
			return $data->errors('validation/user');
		}
		$this->password = $data['password'];
		$this->save();
		return false;
	}
	
	public function add_role($role = 'login')
	{
		$role = new Model_Role(array('name' => $role));
		$this->add('roles', $role);
		$this->save();
	}
	
	public function created()
	{
		return date('F d, Y', $this->created);
	}
	
	public function username($linked = false)
	{
		if(!$linked)
		{
			return $this->username;
		}
		else
		{
			if((bool)$this->option('public'))
			{
				return HTML::anchor('/user/'.$this->slug,$this->username);
			}
			else
			{
				return $this->username;
			}
		}
	}
	
	public function link()
	{
		return $this->username(true);
	}
	
}
