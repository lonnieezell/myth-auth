<?php namespace Myth\Auth\Test;

use Config\Services;

/**
 * Trait AuthTestTrait
 *
 * Provides additional utilities for authentication and authorization
 * during testing.
 */
trait AuthTestTrait
{
    /**
     * Resets the Authentication and Authorization services.
     * Particularly helpful between feature tests.
     */
	protected function resetAuthServices()
	{
		Services::injectMock('authentication', Services::authentication('local', null, null, false));
		Services::injectMock('authorization',  Services::authorization(null, null, null, false));
		$_SESSION = [];
	}
}
