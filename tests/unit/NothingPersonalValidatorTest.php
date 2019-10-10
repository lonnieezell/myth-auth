<?php

use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Authentication\Passwords\NothingPersonalValidator;

class NothingPersonalValidatorTest extends CIUnitTestCase
{

    /**
     * @var CompositionValidator
     */
    protected $validator;

    public function setUp(): void
    {
        parent::setUp();

        $config = new \Myth\Auth\Config\Auth();

        $this->validator = new NothingPersonalValidator();
        $this->validator->setConfig($config);
    }

    public function testCheckFalseOnEmailMatch()
    {
        $user = new \Myth\Auth\Entities\User([
            'email' => 'JoeSmith@example.com',
            'name' => 'Joe Smith'
        ]);

        $password = 'joesmith@example.com';

        $this->assertFalse($this->validator->check($password, $user));
    }

    public function testCheckFalseOnUsernameMatch()
    {
        $user = new \Myth\Auth\Entities\User([
            'username' => 'CaptainJoe'
        ]);

        $password = 'captainjoe';

        $this->assertFalse($this->validator->check($password, $user));
    }

}
