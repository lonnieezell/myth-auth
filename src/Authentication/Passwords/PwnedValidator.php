<?php namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;
use CodeIgniter\Config\Services;
use Myth\Auth\Exceptions\AuthException;

/**
 * Class PwnedValidator
 *
 * Checks if the password has been compromised.
 *
 * NIST recommend to check passwords against those obtained from previous data breaches.
 *
 * @see https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/
 * @see https://pages.nist.gov/800-63-3/sp800-63b.html#sec5
 *
 * @package Myth\Auth\Authentication\Passwords\Validators
 */
class PwnedValidator extends BaseValidator implements ValidatorInterface
{
    /**
     * Error message.
     *
     * @var string
     */
    protected $error;

    /**
     * Suggestion message.
     *
     * @var string
     */
    protected $suggestion;

    /**
     * Checks the password and returns true/false
     * if it passes muster. Must return either true/false.
     * True means the password passes this test and
     * the password will be passed to any remaining validators.
     * False will immediately stop validation process
     *
     * @param string $password
     * @param Entity $user
     *
     * @return bool
     */
    public function check(string $password, Entity $user = null): bool
    {
        $password = trim($password);

        if (empty($password))
        {
            $this->error = lang('Auth.errorPasswordEmpty');

            return false;
        }

        $password   = strtoupper(sha1($password));
        $rangeHash  = substr($password, 0, 5);
        $searchHash = substr($password, 5);

        $client = Services::curlrequest([
            'base_uri' => 'https://api.pwnedpasswords.com/',
        ]);

        $response = $client->get('range/' . $rangeHash, ['headers' => ['Accept' => 'text/plain']]);
        $body = $response->getBody();
        $startPos = strpos($body, $searchHash);
        if($startPos === false)
        {
            return true;
        }
        
        $endPos = strpos($body, PHP_EOL, $startPos);
        $line = substr($body, $startPos, $endPos - $startPos); 
        list($hash, $hits) = explode(':', $line);
        
        $this->error = lang('Auth.errorPasswordPwned', [(int) $hits]);
        $this->suggestion = lang('Auth.suggestPasswordPwned');
        
        return false;
    }

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string
    {
        return $this->error;
    }

    /**
     * Returns a suggestion that may be displayed to the user
     * to help them choose a better password. The method is
     * required, but a suggestion is optional. May return
     * an empty string instead.
     *
     * @return string
     */
    public function suggestion(): string
    {
        return $this->suggestion;
    }
}