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
        //
//        $config->personalFields = [];//['firstname', 'lastname'];

        $this->validator = new NothingPersonalValidator();
        $this->validator->setConfig($config);
    }

    public function testFalseOnPasswordIsEmail()
    {
        $user = new \Myth\Auth\Entities\User(
            [
            'email' => 'JoeSmith@example.com',
            'username' => 'Joe Smith'
            ]
        );

        $password = 'joesmith@example.com';

        $this->assertFalse($this->validator->check($password, $user));
    }

    public function testFalseOnPasswordIsUsernameBackwards()
    {
        $user = new \Myth\Auth\Entities\User(
            [
            'email' => 'JoeSmith@example.com',
            'username' => 'Joe Smith'
            ]
        );

        $password = 'Htims Eoj';

        $this->assertFalse($this->validator->check($password, $user));
    }

    public function testFalseOnPasswordAndUsernameTheSame()
    {
        $user = new \Myth\Auth\Entities\User(
            [
            'email' => 'vampire@example.com',
            'username' => 'Vlad the Impaler'
            ]
        );

        $password = 'Vlad the Impaler';

        $this->assertFalse($this->validator->check($password, $user));
    }

    public function testTrueWhenPasswordHasNothingPersonal()
    {
        $config = new \Myth\Auth\Config\Auth();
        $config->maxSimilarity = 50;
        $config->personalFields = ['firstname', 'lastname'];
        $this->validator->setConfig($config);

        $user = new \Myth\Auth\Entities\User(
            [
            'email' => 'jsmith@example.com',
            'username' => 'JoeS',
            'firstname' => 'Joseph',
            'lastname' => 'Smith',
            ]
        );

        $password = 'opensesame';

        $this->assertTrue($this->validator->check($password, $user));
    }

    /**
     * The dataProvider is a list of passwords to be tested. 
     * Some of them clearly contain elements of the username. 
     * Others are scrambled usernames that may not clearly be troublesome, 
     * but arguably should considered troublesome.
     * 
     * All the passwords are accepted by isNotPersonal() but are
     * rejected by isNotSimilar().
     * 
     *  $config->maxSimilarity = 50; is the highest setting where all tests pass.
     * 
     * @dataProvider passwordProvider
     */
    public function testIsNotPersonalFalsePositivesCaughtByIsNotSimilar($password)
    {
        $user = new \Myth\Auth\Entities\User(
            [
            'username' => 'CaptainJoe',
            'email' => 'JosephSmith@example.com'
            ]
        );

        $config = new \Myth\Auth\Config\Auth();
        $config->maxSimilarity = 50;
        $this->validator->setConfig($config);

        $isNotPersonal = $this->getPrivateMethodInvoker($this->validator, 'isNotPersonal',
            [$password, $user]);

        $isNotSimilar = $this->getPrivateMethodInvoker($this->validator, 'isNotSimilar',
            [$password, $user]);

        $this->assertNotSame($isNotPersonal, $isNotSimilar);
    }

    public function passwordProvider()
    {
        return [
            ['JoeTheCaptain'],
            ['JoeCaptain'],
            ['CaptainJ'],
            ['captainjoseph'],
            ['captjoeain'],
            ['tajipcanoe'],
            ['jcaptoeain'],
            ['jtaincapoe'],
        ];
    }

    /**
     * @dataProvider firstLastNameProvider
     */
    public function testConfigPersonalFieldsValues($firstName, $lastName, $expected)
    {
        $config = new \Myth\Auth\Config\Auth();
        $config->maxSimilarity = 66;
        $config->personalFields = ['firstname', 'lastname'];
        $this->validator->setConfig($config);

        $user = new \Myth\Auth\Entities\User(
            [
            'username' => 'Vlad the Impaler',
            'email' => 'vampire@example.com',
            'firstname' => $firstName,
            'lastname' => $lastName,
            ]
        );

        $password = 'Count Dracula';

        $this->assertSame($expected, $this->validator->check($password, $user));
    }

    public function firstLastNameProvider()
    {
        return [
            ['Count', '', false],
            ['', 'Dracula', false],
            ['Vlad', 'the Impaler', true],
        ];
    }

    /**
     * @dataProvider maxSimilarityProvider
     * 
     * The calculated similarity of 'captnjoe' and 'CaptainJoe' is 88.89.
     * With $config->maxSimilarity = 66; the password should be rejected,
     * but using $config->maxSimilarity = 0; will turn off the calculation 
     * and accept the password. 
     */
    public function testMaxSimilarityZeroTurnsOffSimilarityCalculation($maxSimilarity,
                                                                       $expected)
    {
        $config = new \Myth\Auth\Config\Auth();
        $config->maxSimilarity = $maxSimilarity;
        $this->validator->setConfig($config);

        $user = new \Myth\Auth\Entities\User(
            [
            'username' => 'CaptainJoe',
            'email' => 'joseph@example.com',
            ]
        );

        $password = 'captnjoe';

        $this->assertSame($expected, $this->validator->check($password, $user));
    }

    public function maxSimilarityProvider()
    {
        return[[66, false], [0, true]];
    }

}
