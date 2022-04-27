<?php

namespace Myth\Auth;

/**
 * Class Password
 */
class Password
{
    /**
     * @param string $password Password
     */
    public static function hash(string $password): string
    {
        $config = config('Auth');

        if (
            (defined('PASSWORD_ARGON2I') && $config->hashAlgorithm === PASSWORD_ARGON2I)
            || (defined('PASSWORD_ARGON2ID') && $config->hashAlgorithm === PASSWORD_ARGON2ID)
        ) {
            $hashOptions = [
                'memory_cost' => $config->hashMemoryCost,
                'time_cost'   => $config->hashTimeCost,
                'threads'     => $config->hashThreads,
            ];
        } else {
            $hashOptions = [
                'cost' => $config->hashCost,
            ];
        }

        return password_hash(
            self::preparePassword($password),
            $config->hashAlgorithm,
            $hashOptions
        );
    }

    /**
     * @param string $password Password
     * @param string $hash     Hash
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify(self::preparePassword($password), $hash);
    }

    /**
     * @param string     $hash    Hash
     * @param int|string $algo    Hash algorithm
     * @param array      $options Options
     */
    public static function needsRehash(string $hash, $algo, array $options = []): bool
    {
        return password_needs_rehash($hash, $algo, $options);
    }

    /**
     * @param string $password Password
     */
    protected static function preparePassword(string $password): string
    {
        return base64_encode(hash('sha384', $password, true));
    }
}
