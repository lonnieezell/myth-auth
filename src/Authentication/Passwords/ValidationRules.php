<?php namespace Myth\Auth\Authentication\Passwords;

use Config\Services;
use Myth\Auth\Entities\User;

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
        $checker = service('passwords');
        $user = (function_exists("user") && user()) ? user() : $this->buildUserFromRequest();

        $result = $checker->check($str, $user);

        if ($result === false)
        {
            $error = $checker->error();
        }

        return $result;
    }

    /**
     * Builds a new user instance from the global request.
     *
     * @return User
     */
    protected function buildUserFromRequest()
    {
        $config = config('Auth');
        $fields = array_merge($config->validFields, $config->personalFields);
        $fields[] = 'password';

        $data = service('request')->getPost($fields);

        return new User($data);
    }

}
