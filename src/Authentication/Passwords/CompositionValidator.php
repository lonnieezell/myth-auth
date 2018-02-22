<?php namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;
use Myth\Auth\Exceptions\AuthException;

/**
 * Class CompositionValidator
 *
 * Checks the general makeup of the password.
 *
 * While older composition checks might have included different character
 * groups that you had to include, current NIST standards prefer to simply
 * set a minimum length, a long maximum (128+ chars).
 *
 * @see https://pages.nist.gov/800-63-3/sp800-63b.html#sec5
 *
 * @package Myth\Auth\Authentication\Passwords\Validators
 */
class CompositionValidator extends BaseValidator implements ValidatorInterface
{
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
     * @return mixed
     */
    public function check(string $password, Entity $user=null): bool
    {
        if (empty($this->config->minimumPasswordLength))
        {
            throw AuthException::forUnsetPasswordLength();
        }

        $passed = strlen($password) >= $this->config->minimumPasswordLength;

        return $passed;
    }

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string
    {
        return lang('Auth.errorPasswordLength', [$this->config->minimumPasswordLength]);
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
        return lang('Auth.suggestPasswordLength');
    }
}
