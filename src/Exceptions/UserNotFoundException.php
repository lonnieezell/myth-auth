<?php namespace Myth\Auth\Exceptions;

class UserNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function forUserID(int $id)
    {
        return new self(lang('Auth.userNotFound', [$id]), 404);
    }
}
