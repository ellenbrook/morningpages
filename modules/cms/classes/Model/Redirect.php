<?php defined('SYSPATH') or die('No direct script access.');

class Model_Redirect extends ORM {
	
	public function create_new($from, $to, $code = 302)
	{
		if((bool)ORM::factory('redirect')->where('from','=',$to)->count_all())
		{
			$existing = ORM::factory('redirect')->where('from','=',$to)->find();
			$existing->delete();
			// this redirect will be further redirected because there's an existing redirect
			// Check that we don't end up in a loop
			/*$other = ORM::factory('redirect')->where('from','=',$to)->find();
			if((bool)ORM::factory('redirect')->where('from','=',$other->to)->count_all())
			{
				// Will we evetually end up at the same place?
				while((bool)ORM::factory('redirect')->where('from','=',$other->to)->count_all())
				{
					$other = ORM::factory('redirect')->where('from','=',$other->to)->find();
					if($other->to == $from)
					{
						// We'll end up on the same page == endless loop
						// Delete the redirect from our destination
						$delete = ORM::factory('redirect')->where('from','=',$to)->find();
						$delete->delete();
						break;
					}
				}
			}*/
		}
		$this->from = $from;
		$this->to = $to;
		$this->code = $code;
		$this->save();
	}
	
	public function last()
	{
		if(empty($this->lasthit))
		{
			return '<em>Aldrig</em>';
		}
		return date('d-m-Y H:i',$this->lasthit);
	}
	
}
	