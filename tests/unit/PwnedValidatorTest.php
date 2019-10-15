<?php

use Myth\Auth\Config\Services;
use Config\App;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Myth\Auth\Exceptions\AuthException;
use Myth\Auth\Authentication\Passwords\PwnedValidator;

class PwnedValidatorTest extends CIUnitTestCase
{

    /**
     * @var CompositionValidator
     */
    protected $validator;

    public function setUp(): void
    {
        parent::setUp();

        Services::reset();

        $this->validator = new PwnedValidator();
    }

    public function testCheckFalseOnPwnedPassword()
    {
        $body = implode("\r\n",
            [
                '52D9BC2F7F8F471B3192A0D1FF53F7A103D:2', '52EAAD4687FABF36801DF580FB497C6CECA:1',
                '53623B121FD34EE5426C792E5C33AF8C227:50329', '538F23AA21F516E4767380DE4AB7D30AF9B:2',
                '5421C6ECD4AF2B65598980DDC3BC164ED4B:9', '54355ADA7D7B306C68DAC1548E84B53A10F:1'
            ]
        );

        $response = new Response(new App());
        $response->setBody($body);

        $curlrequest = $this->getMockBuilder('CodeIgniter\HTTP\CURLRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $curlrequest->method('get')->willReturn($response);

        Services::injectMock('curlrequest', $curlrequest);

        $password = 'admin123';

        $this->assertFalse($this->validator->check($password));
    }

    public function testCheckFalseOnPwnedLastInRange()
    {
        $body = "81102569463BE3512984154C66C098B92C5:1\r\n53623B121FD34EE5426C792E5C33AF8C227:22\r\nC528C1B79D1BB387A30C33C5C35B4AC5137:333\r\n621B377E1F35302585D82F1604DF01FE106:1\r\n8371797B4241EBAA28F6A746C4EB2EEDFA3:55555";

        $response = new Response(new App());
        $response->setBody($body);

        $curlrequest = $this->getMockBuilder('CodeIgniter\HTTP\CURLRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $curlrequest->method('get')->willReturn($response);

        Services::injectMock('curlrequest', $curlrequest);

        $password = 'ziplock';

        $this->assertFalse($this->validator->check($password));
    }

    public function testCheckTrueOnNotFound()
    {
        $body = implode("\r\n",
            [
                '02AEBBE44887FF9D7BCCB8437910EB92DE8:1', 'n02EE34E878ABDBFBD2439BC799F85869521:1',
                '033C5EE74BC4C953E2972C7D87BB1858A85:2', '0385DB23CA0658858A494B66A7933955551:1',
                '03B14A9EA4D383220176FDC3B3BC771A415:1', '03BE55564E0C24C43E416F595B743588A27:1'
            ]
        );

        $response = new Response(new App());
        $response->setBody($body);

        $curlrequest = $this->getMockBuilder('CodeIgniter\HTTP\CURLRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $curlrequest->method('get')->willReturn($response);

        Services::injectMock('curlrequest', $curlrequest);

        $password = '!!!gerard!!!abootylicious';

        $this->assertTrue($this->validator->check($password));
    }

    public function testCheckCatchesAndRethrowsCurlExceptionAsAuthException()
    {
        $curlrequest = $this->getMockBuilder('CodeIgniter\HTTP\CURLRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $curlrequest->method('get')
            ->will($this->throwException(HTTPException::forCurlError('7',
                        'Failed to connect')));
        Services::injectMock('curlrequest', $curlrequest);

        $this->expectException(AuthException::class);
        $this->validator->check('opensesame');
    }

}
