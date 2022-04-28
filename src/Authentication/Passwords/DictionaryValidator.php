<?php

namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity\Entity;

/**
 * Class DictionaryValidator
 *
 * Checks passwords against a list of 65k commonly used passwords
 * that was compiled by InfoSec.
 */
class DictionaryValidator extends BaseValidator implements ValidatorInterface
{
    /**
     * Checks the password against the words in the file and returns false
     * if a match is found. Returns true if no match is found.
     * If true is returned the password will be passed to next validator.
     * If false is returned the validation process will be immediately stopped.
     *
     * @param Entity $user
     */
    public function check(string $password, ?Entity $user = null): bool
    {
        // Loop over our file
        $fp = fopen(__DIR__ . '/_dictionary.txt', 'rb');
        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                if ($password === trim($line)) {
                    fclose($fp);

                    $this->error      = lang('Auth.errorPasswordCommon');
                    $this->suggestion = lang('Auth.suggestPasswordCommon');

                    return false;
                }
            }
        }

        fclose($fp);

        return true;
    }
}
