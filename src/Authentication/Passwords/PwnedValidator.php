<?php

namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Myth\Auth\Exceptions\AuthException;

/**
 * Class PwnedValidator
 *
 * Checks if the password has been compromised by checking against
 * an online database of over 555 million stolen passwords.
 *
 * @see https://www.troyhunt.com/ive-just-launched-pwned-passwords-version-2/
 *
 * NIST recommend to check passwords against those obtained from previous data breaches.
 * @see https://pages.nist.gov/800-63-3/sp800-63b.html#sec5
 */
class PwnedValidator extends BaseValidator implements ValidatorInterface
{
    /**
     * Checks the password against the online database and
     * returns false if a match is found. Returns true if no match is found.
     * If true is returned the password will be passed to next validator.
     * If false is returned the validation process will be immediately stopped.
     *
     * @param Entity $user
     */
    public function check(string $password, ?Entity $user = null): bool
    {
        $hashedPword = strtoupper(sha1($password));
        $rangeHash   = substr($hashedPword, 0, 5);
        $searchHash  = substr($hashedPword, 5);

        try {
            $client = service('curlrequest', [
                'base_uri' => 'https://api.pwnedpasswords.com/',
            ]);

            $response = $client->get(
                'range/' . $rangeHash,
                ['headers' => ['Accept' => 'text/plain']]
            );
        } catch (HTTPException $e) {
            $exception = AuthException::forHIBPCurlFail($e);
            service('logger')->error('[ERROR] {exception}', ['exception' => $exception]);

            throw $exception;
        }

        $range    = $response->getBody();
        $startPos = strpos($range, $searchHash);
        if ($startPos === false) {
            return true;
        }

        $startPos += 36; // right after the delimiter (:)
        $endPos = strpos($range, "\r\n", $startPos);
        $hits   = $endPos !== false ? (int) substr($range, $startPos, $endPos - $startPos) : (int) substr($range, $startPos);

        $wording          = $hits > 1 ? lang('Auth.errorPasswordPwnedDatabases') : lang('Auth.errorPasswordPwnedDatabase');
        $this->error      = lang('Auth.errorPasswordPwned', [$password, $hits, $wording]);
        $this->suggestion = lang('Auth.suggestPasswordPwned', [$password]);

        return false;
    }
}
