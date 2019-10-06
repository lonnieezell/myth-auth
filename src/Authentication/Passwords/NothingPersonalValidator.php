<?php namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;

/**
 * Class NothingPersonalValidator
 *
 * Checks password does not contain any personal information
 *
 * @package Myth\Auth\Authentication\Passwords\Validators
 */
class NothingPersonalValidator extends BaseValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    protected $error;
    /**
     * @var string
     */
    protected $suggestion;

    /**
     * Returns true if $password contains no part of the username or the user's email. 
     * Otherwise, it returns false. 
     * If true is returned the password will be passed to next validator.
     * If false is returned the validation process will be immediately stopped.
     *
     * @param string $password
     * @param Entity $user
     *
     * @return boolean
     */
    public function check(string $password, Entity $user=null): bool
    {
        // Don't allow personal information as the password
        if ($user !== null)
        {
            $names = [
                strtolower($user->name),
                strtolower(str_replace(' ', '', $user->name)),
                strtolower(str_replace(' ', '.', $user->name)),
                strtolower(str_replace(' ', '-', $user->name)),
            ];

            $tPassword = strtolower($password);
            if ($tPassword == strtolower($user->email)
                || in_array($tPassword, $names, $user->name)
                || $tPassword == strtolower($user->username)
            )
            {
                $this->error = lang('Auth.errorPasswordPersonal');
                $this->suggestion = lang('Auth.suggestPasswordPersonal');

                return false;
            }
        }

        return true;
    }

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string
    {
        return $this->error ?? '';
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
        return $this->suggestion ?? '';
    }
}
