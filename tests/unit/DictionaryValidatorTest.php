<?php

use Myth\Auth\Authentication\Passwords\DictionaryValidator;

class DictionaryValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CompositionValidator
     */
    protected $validator;

    public function setUp()
    {
        parent::setUp();

        $config = new \Myth\Auth\Config\Auth();

        $this->validator = new DictionaryValidator();
        $this->validator->setConfig($config);
    }

    public function testCheckFalseOnEmptyPassword()
    {
        $password = '';

        $this->assertFalse($this->validator->check($password));
    }

    public function testCheckFalseOnFoundPassword()
    {
        $password = '!!!gerard!!!';

        $this->assertFalse($this->validator->check($password));
    }

    public function testCheckTrueOnNotFound()
    {
        $password = '!!!gerard!!!abootylicious';

        $this->assertTrue($this->validator->check($password));
    }

    public function testCheckFalseOnEmailMatch()
    {
        $user = new \Myth\Auth\Entities\User([
            'email' => 'JoeSmith@example.com'
        ]);

        $password = 'joesmith@example.com';

        $this->assertFalse($this->validator->check($password, $user));
    }

    public function testCheckFalseOnNameMatch()
    {
        $user = new \Myth\Auth\Entities\User([
            'name' => 'Joe Smith'
        ]);

        $password = 'joe smith';

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
