<?php namespace Myth\Auth\Exceptions;

class AuthException extends \DomainException implements ExceptionInterface
{
    public static function forInvalidModel(string $model)
    {
        return new self(lang('Auth.invalidModel', [$model]), 500);
    }
}
