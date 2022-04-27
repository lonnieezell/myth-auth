<?php

use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Authentication\Passwords\DictionaryValidator;
use Myth\Auth\Config\Auth;

/**
 * @internal
 */
final class DictionaryValidatorTest extends CIUnitTestCase
{
    protected DictionaryValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $config = new Auth();

        $this->validator = new DictionaryValidator();
        $this->validator->setConfig($config);
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
}
