<?php defined('SYSPATH') or die('No direct script access.'); 

class Log extends Kohana_Log {
	
	public static function exception($msg, $e)
	{
		$log = Kohana_Log::instance()->add(log::ERROR, $msg.' || Exception message: :emsg, file: :file, line: :line', array(
			':emsg' => $e->getMessage(),
			':file' => $e->getFile(),
			':line' => $e->getLine()
		));
	}
	
	public static function adderror($msg, $level = 'error')
	{
		$log = self::instance();
		switch($level)
		{
			// Preparing for future events
			case 'emergency':
			case 'error':
			case 'note':
			default:
				$log->add(log::ERROR, $msg);
				break;
		}
	}
	
}
