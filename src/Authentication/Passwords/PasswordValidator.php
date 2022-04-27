<?php

namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;
use Myth\Auth\Exceptions\AuthException;

/**
 * A "meta-validator" for the other password validation classes.
 * Note that this is not itself a ValidatorInterface.
 */
class PasswordValidator extends BaseValidator
{
    public function __construct(AuthConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Checks a password against all of the Validators specified
     * in `$passwordValidators` setting in Config\Auth.php.
     *
     * @param User $user
     */
    public function check(string $password, ?User $user = null): bool
    {
        if (null === $user) {
            throw AuthException::forNoEntityProvided();
        }

        $password = trim($password);

        if (empty($password)) {
            $this->error = lang('Auth.errorPasswordEmpty');

            return false;
        }

        $valid = false;

        foreach ($this->config->passwordValidators as $className) {
            $class = new $className();
            $class->setConfig($this->config);

            if ($class->check($password, $user) === false) {
                $this->error      = $class->error();
                $this->suggestion = $class->suggestion();

                $valid = false;
                break;
            }

            $valid = true;
        }

        return $valid;
    }
}
