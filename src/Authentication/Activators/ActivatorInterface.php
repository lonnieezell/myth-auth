<?php namespace Myth\Auth\Authentication\Activators;

use CodeIgniter\Entity;

/**
 * Interface ActivatorInterface
 *
 * @package Myth\Auth\Authentication\Activators
 */
interface ActivatorInterface
{
    /**
     * Send activation message to user
     *
     * @param User $user
     *
     * @return mixed
     */
    public function send(Entity $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string;

}
