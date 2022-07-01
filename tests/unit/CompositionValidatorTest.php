<?php

use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Authentication\Passwords\CompositionValidator;
use Myth\Auth\Config\Auth;

/**
 * @internal
 */
final class CompositionValidatorTest extends CIUnitTestCase
{
    protected CompositionValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $config                        = new Auth();
        $config->minimumPasswordLength = 8;

        $this->validator = new CompositionValidator();
        $this->validator->setConfig($config);
    }

    public function testCheckFalse()
    {
        $password = '1234';

        $this->assertFalse($this->validator->check($password));
    }

    public function testCheckTrue()
    {
        $password = '1234567890';

        $this->assertTrue($this->validator->check($password));
    }
}
