<?php

namespace Myth\Auth\Authentication\Passwords;

use CodeIgniter\Entity;

interface ValidatorInterface
{
    /**
     * Checks the password and returns true/false
     * if it passes muster. Must return either true/false.
     * True means the password passes this test and
     * the password will be passed to any remaining validators.
     * False will immediately stop validation process
     */
    public function check(string $password, ?Entity $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string;

    /**
     * Returns a suggestion that may be displayed to the user
     * to help them choose a better password. The method is
     * required, but a suggestion is optional. May return
     * an empty string instead.
     */
    public function suggestion(): string;
}
