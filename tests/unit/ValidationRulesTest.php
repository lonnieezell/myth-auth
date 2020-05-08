<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Myth\Auth\Authentication\Passwords\ValidationRules;


class ValidationRulesTest extends CIUnitTestCase
{
    protected $validation;
    protected $config = [
        'ruleSets'      => [
            ValidationRules::class,
        ],
    ];

    //--------------------------------------------------------------------

    protected function setUp(): void
    {
        parent::setUp();

        Services::reset(true);

        $this->validation = new Validation((object) $this->config, \Config\Services::renderer());
        $this->validation->reset();

        $_REQUEST = [];
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordLongRule()
    {
        $rules = [
            'password' => 'strong_password[]',
        ];

        $data = [
            'email'    => 'john@smith.com',
            'password' => '!!!gerard!!!abootylicious',
        ];

        $this->validation->setRules($rules);

        $this->assertTrue($this->validation->run($data));
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordLongRuleWithPostRequest()
    {
        $_REQUEST = $data = [
            'email'    => 'john@smith.com',
            'password' => '!!!gerard!!!abootylicious',
        ];

        $request = service('request');
        $request->setMethod('post')->setGlobal('post', $data);

        $this->validation->setRules([
            'password' => 'strong_password[]',
        ]);

        $result = $this->validation->withRequest($request)->run();
        $this->assertTrue($result);
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordLongRuleWithRawInputRequest()
    {
        $data = [
            'email'    => 'john@smith.com',
            'password' => '!!!gerard!!!abootylicious',
        ];

        $request = service('request');
        $request->setMethod('patch')->setBody(http_build_query($data));

        $this->validation->setRules([
            'password' => 'strong_password[]',
        ]);

        $result = $this->validation->withRequest($request)->run();
        $this->assertTrue($result);
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordShortRuleWithPostRequest()
    {
        $_REQUEST = $data = [
            'email'    => 'john@smith.com',
            'password' => '!!!gerard!!!abootylicious',
        ];

        $request = service('request');
        $request->setMethod('post')->setGlobal('post', $data);

        $this->validation->setRules([
            'password' => 'strong_password',
        ]);

        $result = $this->validation->withRequest($request)->run();
        $this->assertTrue($result);
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordShortRuleWithErrors()
    {
        $_REQUEST = $data = [
            'email'    => 'john@smith.com',
            'password' => 'john12345',
        ];

        $request = service('request');
        $request->setMethod('post')->setGlobal('post', $data);

        $this->validation->setRules([
            'password' => 'strong_password',
        ]);

        $result = $this->validation->withRequest($request)->run();
        $this->assertFalse($result);
        $this->assertEquals([
            'password' => 'Passwords cannot contain re-hashed personal information.'
        ], $this->validation->getErrors());
    }

    //--------------------------------------------------------------------

    public function testStrongPasswordLongRuleWithErrors()
    {
        $_REQUEST = $data = [
            'email'    => 'john@smith.com',
            'password' => 'john12345',
        ];

        $request = service('request');
        $request->setMethod('patch')->setBody(http_build_query($data));

        $this->validation->setRules([
            'password' => 'strong_password[]',
        ]);

        $result = $this->validation->withRequest($request)->run();
        $this->assertFalse($result);
        $this->assertEquals([
            'password' => 'Passwords cannot contain re-hashed personal information.'
        ], $this->validation->getErrors());
    }

}
