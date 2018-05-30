<?php namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;

/**
 * Class DictionaryValidator
 *
 * Checks passwords against a list of 65k commonly used passwords
 * that was compiled by InfoSec.
 *
 * @package Myth\Auth\Authentication\Passwords\Validators
 */
class DictionaryValidator extends BaseValidator implements ValidatorInterface
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
        $password = trim($password);

        if (empty($password))
        {
            $this->error = lang('Auth.errorPasswordEmpty');

            return false;
        }

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

        // Loop over our file
        $fp = fopen(__DIR__ .'/_dictionary.txt', 'r');
        if ($fp)
        {
            while (($line = fgets($fp, 4096)) !== false)
            {
                if ($password == trim($line))
                {
                    fclose($fp);

                    $this->error = lang('Auth.errorPasswordCommon');
                    $this->suggestion = lang('Auth.suggestPasswordCommon');
                    return false;
                }
            }
        }

        fclose($fp);

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
