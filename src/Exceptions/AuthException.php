<?php namespace Myth\Auth\Exceptions;

use CodeIgniter\HTTP\Exceptions\HTTPException;

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

    /**
     * When the cURL request (to Have I Been Pwned) in PwnedValidator 
     * throws a HTTPException it is re-thrown as this one
     *
     * @return AuthException
     */
    public static function forHIBPCurlFail(HTTPException $e)
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * When no User Entity is passed into PasswordValidator::check()
     *
     * @return AuthException
     */
    public static function forNoEntityProvided()
    {
        return new self(lang('Auth.noUserEntity'), 500);
    }

}
