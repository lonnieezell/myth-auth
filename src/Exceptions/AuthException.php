<?php namespace Myth\Auth\Exceptions;

use Myth\Auth\Config\Auth;

class AuthException extends \DomainException implements ExceptionInterface
{
    public static function forInvalidModel(string $model)
    {
        return new self(lang('Auth.invalidModel', [$model]), 500);
    }

    /**
     * For when the developer attempts to authenticate
     * with too many credentials.
     *
     * @return AuthException
     */
    public static function forTooManyCredentials()
    {
        return new self(lang('Auth.tooManyCredentials'), 500);
    }

    /**
     * For when the developer passed invalid field along
     * with 'password' when attempting to validate a user.
     *
     * @param string $key
     *
     * @return AuthException
     */
    public static function forInvalidFields(string $key)
    {
        return new self(lang('Auth.invalidFields', [$key]), 500);
    }

    /**
     * Fires when no minimumPasswordLength has been set
     * in the Auth config file.
     *
     * @return AuthException
     */
    public static function forUnsetPasswordLength()
    {
        return new self(lang('Auth.unsetPasswordLength'), 500);
    }
}
