<?php

namespace Myth\Auth\Entities\Cast;

use CodeIgniter\Entity\Cast\BaseCast;

/**
 * Class BooleanCast
 */
class BooleanCast extends BaseCast
{
    /**
     * {@inheritDoc}
     */
    public static function get($value, array $params = []): bool
    {
        // PostgreSQL (via PDO, etc.) returns (string)'f' or 't' for boolean
        // fields
        return (bool) $value && $value !== 'f';
    }
}
