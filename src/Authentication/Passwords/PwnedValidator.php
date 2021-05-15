<?php

namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Myth\Auth\Exceptions\AuthException;

/**
 * Class PwnedValidator
 *
 * Checks if the password has been compromised by checking against 
 * an online database of over 555 million stolen passwords.
 * @see https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/
 *
 * NIST recommend to check passwords against those obtained from previous data breaches.
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
     * Checks the password against the online database and 
     * returns false if a match is found. Returns true if no match is found.
     * If true is returned the password will be passed to next validator.
     * If false is returned the validation process will be immediately stopped.
     *
     * @param string $password
     * @param Entity $user
     *
     * @return boolean
     */
    public function check(string $password, Entity $user = null): bool
    {
        $hashedPword = strtoupper(sha1($password));
        $rangeHash = substr($hashedPword, 0, 5);
        $searchHash = substr($hashedPword, 5);

        try
        {
            $client = service('curlrequest', [
                    'base_uri' => 'https://api.pwnedpasswords.com/',
            ]);

            $response = $client->get('range/'.$rangeHash,
                ['headers' => ['Accept' => 'text/plain']]
            );
        }
        catch(HTTPException $e)
        {
            $exception = AuthException::forHIBPCurlFail($e);
            service('logger')->error('[ERROR] {exception}', ['exception' => $exception]);
            throw $exception;
        }

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

        $wording = $hits > 1 ? lang('Auth.errorPasswordPwnedDatabases') : lang('Auth.errorPasswordPwnedDatabase');
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
