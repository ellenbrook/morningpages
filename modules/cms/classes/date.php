<?php defined('SYSPATH') or die('No direct script access.');

class Date extends Kohana_Date
{
	
	public static function dkdate($str, $timestamp = false)
	{
		$time = date($str);
		if($timestamp) $time = date($str, $timestamp);
		$names = array(
			'January',
			'February',
			'March',
			'May',
			'June',
			'July',
			'October',
			'Oct',
			'Mon',
			'Tue',
			'Wed',
			'Thu',
			'Fri',
			'Sat',
			'Sun'
		);
		$dknames = array(
			'Januar',
			'Februar',
			'Marts',
			'Maj',
			'Juni',
			'Juli',
			'Oktober',
			'Okt',
			'Man',
			'Tir',
			'Ons',
			'Tor',
			'Fre',
			'Lør',
			'Søn'
		);
		return str_replace($names, $dknames, $time);
	}
	
}
