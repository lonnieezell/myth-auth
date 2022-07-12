# Extending

## Core classes

`Authentication` and `Authorization` have their own interfaces and configurations. See the
appropriate docs for each component:
* [Authentication](/docs/authentication.md)
* [Authorization](/docs/authorization.md)

## Database

This library is intentionally slim on user identifying information, having only the fields necessary for
authentication and authorization. You will likely want to add fields like a user's name or phone number,
which you can do by creating your own migration with these fields.

## Models

The entire library makes use of CodeIgniter's `Factories` to locate the best instances for models.
You can supply your own models by duplicating the file names in your **app/Models/** folder
and applying your customizations. Using `auth:publish` is a good starting point for providing these
overriding models.

Similarly, extending the models allows you to provide a new return type and make use of new
Entities with your own casts and class methods.

## Views

Myth:Auth uses its own views by default, but you may want to update these in order to change
the appearance or add form fields. Create your own **app/Config/Auth.php** as an extension
of the package's version and add a `$views` property as an array of "method => view path".
Here are the default values:

```php
	public $views = [
		'login'		      => 'Myth\Auth\Views\login',
		'register'		  => 'Myth\Auth\Views\register',
		'forgot'		  => 'Myth\Auth\Views\forgot',
		'reset'		      => 'Myth\Auth\Views\reset',
		'emailForgot'	  => 'Myth\Auth\Views\emails\forgot',
		'emailActivation' => 'Myth\Auth\Views\emails\activation',
	];
```

Every view is wrapped in Myth:Auth's default layout, which can also be changed by modifying
the `$viewLayout` property in the same config file:

```php
	public $viewLayout = 'Myth\Auth\Views\layout';
```

## Example

You are developing a web app and need to track users' names and phone numbers in addition
to the regular fields used for authentication. First step is to modify the database to
add these fields, so you create **app/Database/Migrations/20190603101528_alter_table_users.php**
and define `firstname`, `lastname`, and `phone`
(see [an example migration](../examples/20190603101528_alter_table_users.php)).

Next you need to let the `UserModel` know about these additional fields. Myth:Auth uses
`Factories` to load its `UserModel` so if it finds a corresponding file in **app/Models** it
will use yours instead. Second step is to create **app/Models/UserModel.php** and update the
list of allowed fields to include the new fields:

```php
<?php namespace App\Models;

use Myth\Auth\Models\UserModel as MythModel;

class UserModel extends MythModel
{
    protected $allowedFields = [
        'email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'deleted_at',
        'firstname', 'lastname', 'phone',
    ];
}
```

> **Notice** The new model extends Myth's version, so all the other necessary properties and methods are still available.

Next, you would like to add a shorthand for displaying a user's full name, as well as a
default name in case a user has not supplied one yet. We will need a new Entity for this,
so create **app/Entities/User.php**:

```php
<?php namespace App\Entities;

use Myth\Auth\Entities\User as MythUser;

class User extends MythUser
{
    /**
     * Default attributes.
     * @var array
     */
    protected $attributes = [
    	'firstname' => 'Guest',
    	'lastname'  => 'User',
    ];

	/**
	 * Returns a full name: "first last"
	 *
	 * @return string
	 */
	public function getName()
	{
		return trim(trim($this->attributes['firstname']) . ' ' . trim($this->attributes['lastname']));
	}
}
```

> **Notice** We use the Entity method naming convention "getXxxx()" so our method is accessible as a magic property: `echo $user->name;`

Now that we have our new entity, we need to update our `UserModel` to return it instead of the
default Myth:Auth version:

```php
class UserModel extends MythModel
{
    protected $returnType = 'App\Entities\User';
	...
```

Finally, we want to collect phone numbers during the initial registration. In order to do this
we will need our own View, so create **app/Views/register.php** (probably as a copy of
[the original](/src/Views/register.php)) and add your new field:

```php
	<div class="form-group">
		<label for="phone">Phone number</label>
		<input type="phone" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" name="phone" placeholder="Phone number" value="<?= old('phone') ?>">
	</div>
```

Because the registration process is using our new `UserModel`, it will automatically except
the new form value.
