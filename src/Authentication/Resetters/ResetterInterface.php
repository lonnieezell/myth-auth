<?php

namespace Myth\Auth\Authentication\Resetters;

use Myth\Auth\Entities\User;

/**
 * Interface ResetterInterface
 */
interface ResetterInterface
{
    /**
     * Send reset message to user
     *
     * @param User $user
     */
    public function send(?User $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string;
}
