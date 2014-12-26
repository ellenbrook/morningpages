<?php

Class AchievementsTest extends Unittest_TestCase {
	
	function testBoolReturns()
	{
		$user = ORM::factory('User')
			->where('username','=','daniel')
			->find();
		$bools = array(
			true,
			false
		);
		
		$this->assertTrue(
			in_array(achievement::check_newbie($user), $bools),
			'check_newbie did not return a bool'
		);
		$this->assertTrue(
			in_array(achievement::check_hattrick($user), $bools),
			'check_hattrick did not return a bool'
		);
		$this->assertTrue(
			in_array(achievement::check_7daystreak($user), $bools),
			'check_7daystreak did not return a bool'
		);
		$this->assertTrue(
			in_array(achievement::check_hotstreak($user), $bools),
			'check_hotstreak did not return a bool'
		);
	}
	
}
