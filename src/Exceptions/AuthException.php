<?php namespace Myth\Auth\Exceptions;

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
}
