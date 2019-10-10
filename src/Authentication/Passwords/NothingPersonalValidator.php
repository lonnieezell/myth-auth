<?php

namespace Myth\Auth\Authentication\Passwords;

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
     * Returns true if $password contains no part of the username 
     * or the user's email. Otherwise, it returns false. 
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
        $userName = \strtolower($user->username);
        $email = \strtolower($user->email);
        $password = \strtolower($password);
        
        // remove all spaces from $userName and password
        $collapsedUserName = str_replace(' ', '', $userName);
        $collapsedPassword = str_replace(' ', '', $password);

        $valid = true;
        if($userName === $email ||
            $password === $email ||
            $password === $userName ||
            $collapsedPassword === $userName ||
            $collapsedPassword === $collapsedUserName)
        {
            $valid = false;
        }

        if($valid)
        {
            // Use spaces to replace non-word characters and underscores in $password
            $strippedPassword = trim(preg_replace('/[\W_]+/', ' ', $password));
            $words = explode(' ', $strippedPassword);

            $trivial = [
                'a', 'an', 'and', 'as', 'at', 'but', 'for',
                'if', 'in', 'not', 'of', 'or', 'so', 'the', 'then'
            ];

            // search for password pieces in username - ignore trivials
            foreach($words as $word)
            {
                if(\in_array($word, $trivial))
                {
                    continue;
                }

                if(\strpos($userName, $word) !== false)
                {
                    $valid = false;
                    break;
                }
            }
        }

        if( ! $valid)
        {
            $this->error = lang('Auth.errorPasswordPersonal');
            $this->suggestion = lang('Auth.suggestPasswordPersonal');
        }

        return $valid;
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
