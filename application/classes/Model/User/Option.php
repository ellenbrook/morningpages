<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Option extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
		'theme' => array(),
		'timezone' => array()
	);
	
	public function labels()
	{
		return array(
			'reminder'				=> 'Reminder',
			'reminder_hour'			=> 'Reminder hour',
			'reminder_minute'		=> 'Reminder minute',
			'reminder_meridiem'		=> 'Reminder meridiem',
			'timezone_id'			=> 'Timezone',
			'theme_id'				=> 'Theme',
			'privacymode'			=> 'Privacymode',
			'privacymode_minutes'	=> 'Privacimode minutes',
			'hemingwaymode'			=> 'Hemingway mode',
			'public'				=> 'Public',
			'rtl'					=> 'Right to left',
		);
	}
	
	public function filters()
	{
		return array(
			'user_id' => array(
				array('Security::xss_clean', array(':value'))
			),
			'reminder' => array(
				array('Security::xss_clean', array(':value')),
				array(array($this,'convert_weird_to_bool'), array(':value'))
			),
			'reminder_hour' => array(
				array('Security::xss_clean', array(':value'))
			),
			'reminder_minute' => array(
				array('Security::xss_clean', array(':value'))
			),
			'reminder_meridiem' => array(
				array('Security::xss_clean', array(':value'))
			),
			'timezone_id' => array(
				array('Security::xss_clean', array(':value'))
			),
			'theme_id' => array(
				array('Security::xss_clean', array(':value'))
			),
			'privacymode' => array(
				array('Security::xss_clean', array(':value')),
				array(array($this,'convert_weird_to_bool'), array(':value'))
			),
			'privacymode_minutes' => array(
				array('Security::xss_clean', array(':value'))
			),
			'hemingwaymode' => array(
				array('Security::xss_clean', array(':value')),
				array(array($this,'convert_weird_to_bool'), array(':value'))
			),
			'public' => array(
				array('Security::xss_clean', array(':value')),
				array(array($this,'convert_weird_to_bool'), array(':value'))
			),
			'rtl' => array(
				array('Security::xss_clean', array(':value')),
				array(array($this,'convert_weird_to_bool'), array(':value'))
			)
		);
	}
	
	public function convert_weird_to_bool($value)
	{
		if($value == 'true' || $value === 1)
		{
			return 1;
		}
		if($value == 'false' || $value === 0)
		{
			return 0;
		}
	}
	
	// Erh...?
	public function rules()
	{
		/*
		return array(
			'user_id' => array(
				array('not_empty'),
				array('numeric'),
				array(array($this, 'verify_userid'), array('user_id',':value'))
			),
			'reminder' => array(
				array('not_empty'),
				array(array($this, 'verify_int_bool'), array('reminder',':value'))
			),
			'reminder_hour' => array(
				array('not_empty'),
				array('numeric'),
				array('range', array(':value',0,12))
			),
			'reminder_minute' => array(
				array('not_empty'),
				array('numeric'),
				array('range', array(':value',0,60))
			),
			'reminder_meridiem' => array(
				array('not_empty'),
				array(array($this, 'verify_ampm'), array('reminder_meridiem',':value'))
			),
			'timezone_id' => array(
				array('not_empty'),
				array('numeric'),
				array(array($this, 'verify_available_timezone'), array('timezone_id',':value'))
			),
			'theme_id' => array(
				array('not_empty'),
				array('numeric'),
				array(array($this, 'verify_available_theme'), array('theme_id',':value'))
			),
			'privacymode' => array(
				array('not_empty'),
				array(array($this, 'verify_int_bool'), array('privacymode',':value'))
			),
			'privacymode_minutes' => array(
				array('not_empty'),
				array('numeric'),
				array('range', array(':value',0,60))
			),
			'hemingwaymode' => array(
				array('not_empty'),
				array(array($this, 'verify_int_bool'), array('hemingwaymode',':value'))
			),
			'public' => array(
				array('not_empty'),
				array(array($this, 'verify_int_bool'), array('public',':value'))
			),
		);*/
		return array();
	}
	
	public function verify_available_theme($field, $value)
	{
		if($value == 0)
		{
			// Standard theme
			return true;
		}
		return (bool)ORM::factory('Theme',$value)->loaded();
	}
	
	public function verify_available_timezone($field, $value)
	{
		return (bool)ORM::factory('Timezone',$value)->loaded();
	}
	
	public function verify_ampm($field, $value)
	{
		return ($value == 'am' || $value == 'pm');
	}
	
	// yeah.. I know
	public function verify_int_bool($field, $value)
	{
		if($value === 'true' || $value === 'false')
		{
			return true;
		}
		if($value === true || $value === false)
		{
			return true;
		}
		if((int)$value === 1 || (int)$value === 0)
		{
			return true;
		};
		return false;
	}
	
	public function verify_userid($field, $value)
	{
		return $value == user::get()->id;
	}
	
}
