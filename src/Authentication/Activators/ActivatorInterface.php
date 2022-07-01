<?php

namespace Myth\Auth\Authentication\Activators;

use Myth\Auth\Entities\User;

/**
 * Interface ActivatorInterface
 */
interface ActivatorInterface
{
    /**
     * Send activation message to user
     *
     * @param User $user
     */
    public function send(?User $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string;
}
