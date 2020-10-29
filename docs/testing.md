# Testing

**Myth:Auth** comes with a few classes to assist with testing authentication in your app.
All test classes are in the `Myth\Auth\Test` namespace and should be ignored in a
production environment.

## Fakers

Fakers are `Model` extensions pre-configured to generate random Entities to be used with
CodeIgniter's `Fabricator` class. These are very handy during testing if you want to create
an object on-the-fly:

```
use CodeIgniter\Test\CIUnitTestCase;
use Myth\Auth\Test\Fakers\UserFaker;

class AuthTest extends CIUnitTestCase
{
	public testUserCanFoo
	{
		helper('test');

		$user = fake(UserFaker::class);

		$this->assertTrue($user->can('foo'));
	}
}
```

The following Fakers are available, corresponding to library models:
* `GroupFaker`
* `PermissionFaker`
* `UserFaker`.

## AuthTestTrait

A trait you may add to your Test Case that provides some convenience methods that are
particularly useful during Feature Testing.

> Tip: Because these method require no parameters you may add them to your Test Case's `$setUpMethods` for automated execution

**createAuthUser(bool $login = true, array $overrides = []): User**

Creates a new random `User` from the `UserFaker` and any `$overrides` data provided.
If `$login` is true (default) it will log in the new user. Returns the new `User` entity.

**resetAuthServices()**

Resets the Authentication and Authorization services. Particularly handy to make sure all
auth data is cleared between test calls.
