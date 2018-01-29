<?php namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Services;

/**
 * Class ValidationRules
 *
 * Provides auth-related validation rules for CodeIgniter 4.
 *
 * To use, add this class to Config/Validation.php, in the
 * $rulesets array.
 *
 * @package Myth\Auth\Authentication\Passwords
 */
class ValidationRules
{
    /**
     * A validation helper method to check if the passed in
     * password will pass all of the validators currently defined.
     *
     * Handy for use in validation, but you will get a slightly
     * better security if this is done manually, since you can
     * personalize based on a specific user at that point.
     *
     * @param string      $str
     * @param string|null $error
     *
     * @return bool
     */
    public function strong_password(string $str, string &$error = null)
    {
        $checker = Services::passwords();

        $result = $checker->check($str);

        if ($result === false)
        {
            $error = $checker->error();
        }

        return $result;
    }

}
