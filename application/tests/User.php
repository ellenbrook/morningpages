<?php

Class UserTest extends Unittest_TestCase {
	
	private $user = false;
	
	function __construct()
	{
		$this->user = ORM::factory('User')
			->where('username','=','daniel')
			->find();
	}
	
	function testAddEvent()
	{
		$this->assertInstanceOf(
			'Model_User_Event',
			$this->user->add_event('Phpunit testevent'),
			'$user->add_event() did not return an instance of Model_User_Event'
		);
	}
	
	function testInfo()
	{
		$this->assertArrayHasKey(
			'id',
			$this->user->info(),
			'$user->info() did not return an array with id key'
		);
	}
	
	function testDoingChallenge()
	{
		$this->assertInternalType(
			'bool',
			$this->user->doing_challenge(),
			'$user->doing_challenge() did not return a bool'
		);
	}
	
	function testTime()
	{
		$this->assertInstanceOf(
			'DateTime',
			$this->user->time(),
			'$user->time() did not return instance of DateTime'
		);
	}
	
	function testTimestamp()
	{
		$this->assertInternalType(
			'int',
			$this->user->timestamp(),
			'$user->timestamp() did not return a timestamp'
		);
	}
	
	function testGetNextReminder()
	{
		$this->assertInternalType(
			'int',
			$this->user->get_next_reminder()
		);
	}
	
	function testWroteToday()
	{
		$this->assertInternalType(
			'bool',
			$this->user->wrote_today(),
			'$user->wrote_today() dod not return a bool'
		);
	}
	
	function testVotedon()
	{
		$this->assertInternalType(
			'bool',
			$this->user->votedon(1),
			'$user->votedon() did not return a bool'
		);
	}
	
	function testGravatar()
	{
		$this->assertStringStartsWith('http://', $this->user->gravatar(), '$user->gravatar() did not return a URL');
	}
	
	function testRules()
	{
		$this->assertTrue(
			is_array($this->user->rules()),
			'$user->rules() did not return an array'
		);
	}
	
	function testVerifyAvailableTheme()
	{
		$this->assertInternalType(
			'bool',
			$this->user->verify_available_theme(NULL, 1),
			'$user->verify_available_theme() did not return a bool'
		);
	}
	
	function testGetPasswordValidation()
	{
		$this->assertInstanceOf(
			'Validation',
			$this->user->get_password_validation(array()),
			'$this->user->get_password_validation() did not return instance of Validation'
		);
	}
	
	function testGetLastLogin()
	{
		$this->assertInternalType(
			'string',
			$this->user->get_last_login(),
			'$user->get_last_login() did not return a string'
		);
	}
	
	function testLabels()
	{
		$this->assertInternalType('array', $this->user->labels(), '$user->labels() did not return an array');
	}
	
	function testHasRole()
	{
		$this->assertInternalType('bool', $this->user->has_role('login'), '$user->has_role() did not return a bool');
	}
	
	function testSave()
	{
		$this->assertInstanceOf('Model', $this->user->save(), '$user->save() failed?');
	}
	
	function testOption()
	{
		$this->assertNotInstanceOf('Kohana_Exception', $this->user->option('timezone_id'), '$user->option(timezone_id) threw exception');
	}
	
	function testGetRoles()
	{
		$this->assertInstanceOf('Database_MySQL_Result', $this->user->get_roles());
	}
	
	function testGetUrl()
	{
		$this->assertInternalType('string', $this->user->get_url());
	}
	
	function testCreated()
	{
		$this->assertInternalType(
			'string',
			$this->user->created(),
			'$user->created() did not return a string'
		);
	}
	
	function testUsername()
	{
		$this->assertInternalType(
			'string',
			$this->user->username(),
			'$user->username() did not return a string'
		);
	}
	
	function testLink()
	{
		$this->stringStartsWith(
			'<a ',
			$this->user->link(),
			'$user->link() did not return an anchor'
		);
	}
	
}
