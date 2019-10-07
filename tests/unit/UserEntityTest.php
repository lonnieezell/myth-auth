<?php

use Myth\Auth\Entities\User;
use CodeIgniter\Test\CIUnitTestCase;

class UserEntityTest extends CIUnitTestCase
{
    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User(['username' => 'conscious4u', 'email' => 'jiminy.cricket@example.com']);
    }
    
	public function testGetSetPermissions()
	{
		$this->assertEmpty($this->user->getPermissions());
		
		$permissions = [1, 2, 4, 5];
		$this->user->setPermissions($permissions);

		$this->assertEquals($permissions, $this->user->getPermissions());
	}
}
