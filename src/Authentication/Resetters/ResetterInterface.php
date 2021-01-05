<?php namespace Myth\Auth\Authentication\Resetters;

use CodeIgniter\Entity;
use Myth\Auth\Entities\User;
/**
 * Interface ResetterInterface
 *
 * @package Myth\Auth\Authentication\Resetters
 */
interface ResetterInterface
{
    /**
     * Send reset message to user
     *
     * @param User $user
     *
     * @return bool
     */
    public function send(User $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     *
     * @return string
     */
    public function error(): string;
}
