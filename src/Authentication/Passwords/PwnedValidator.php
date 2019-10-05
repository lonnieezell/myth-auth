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
     * Checks the password and returns true if it passes this test, or false if not.
     * True means the password will be passed to any remaining validators.
     * False will immediately stop validation process
     *
     * @param string $password
     * @param Entity $user
     *
     * @return boolean
     */
    public function check(string $password, Entity $user = null): bool
    {
        $pWord = trim($password);

        if (empty($pWord))
        {
            $this->error = lang('Auth.errorPasswordEmpty');

            return false;
        }

        $hashedPword = strtoupper(sha1($pWord));
        $rangeHash   = substr($hashedPword, 0, 5);
        $searchHash  = substr($hashedPword, 5);

        $client = Services::curlrequest([
            'base_uri' => 'https://api.pwnedpasswords.com/',
        ]);

        $response = $client->get('range/'.$rangeHash,
            ['headers' => ['Accept' => 'text/plain']]
        );
        $range = $response->getBody();
        $startPos = strpos($range, $searchHash);
        if($startPos === false)
        {
            return true;
        }
        
        $startPos += 36; // right after the delimiter (:)
        $endPos = strpos($range, "\r\n", $startPos);
        if($endPos !== false)
        {
            $hits = (int) substr($range, $startPos, $endPos - $startPos);
        }
        else
        {
            // match is the last item in the range which does not end with "\r\n"
            $hits = (int) substr($range, $startPos);
        }
        
        $wording = $hits > 1 ? "databases" : "a database";
        $this->error = lang('Auth.errorPasswordPwned', [$password, $hits, $wording]);
        $this->suggestion = lang('Auth.suggestPasswordPwned', [$password]);
        
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