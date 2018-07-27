# The Auth Trait

The Auth Trait can be used on any Controller, and provides a number of convenience methods that work with both 
Authentication and Authorization.

## Protecting Controllers

Before you can protect a controller, you must first attach the `AuthTrait` to your controller.

	class SomeController extends Controller 
	{
		use \Myth\Auth\AuthTrait;
	}

Once attached, you would call one of the `restrict...` methods, which will load up the Authorization and Authentication 
classes, as defined in the auth config file, and attempt to log the user in if they're remembered or currently logged in.

### restrict()

This method simply ensures that the user is logged in. You can pass it a URL to redirect to in the case they are not 
logged in. If no URI is provided, it will try to use the route named 'login'.

	class SomeController extends Controller 
	{
		use \Myth\Auth\AuthTrait;
		
		public function __construct() 
		{
			$this->restrict( site_url('my/login/url') );
		}
	}

### restrictToGroups()

Like the restrict method, this will ensure that a user is logged in, but you can also pass one or more groups as 
the first parameter to ensure that the current user is in one of those groups. The groups parameter can be either a 
single element or an array. Each group can be either the ID or the group name. The second parameter accepts the URL to 
redirect to if they are not allowed here.

	// A single group, by ID
	$this->restrictToGroups(1, site_url('login') );
	
	// A single group, by name
	$this->restrictToGroups('admins', site_url('login') );
	
	// Multiple Groups, by ID
	$this->restrictToGroups([1, 2], site_url('login') );
	
	// Multiple Groups, by name
	$this->restrictToGroups(['admins', 'moderators'], site_url('login') );

### restrictWithPermissions()

Like the restrict method, this will ensure that a user is logged in, but you can also pass one or more permissions as 
the first parameter to ensure that the current user is a member of a group that has one of those permissions. The 
permissions parameter can be either a single element or an array. Each permission can be either the ID or the 
permission name. The second parameter accepts the URL to redirect to if they are not allowed here.

	// A single permission, by ID
	$this->restrictWithPermissions(1, site_url('login') );
	
	// A single permission, by name
	$this->restrictWithPermissions('blog.posts.view', site_url('login') );
	
	// Multiple permissions, by ID
	$this->restrictWithPermissions([1, 2], site_url('login') );
	
	// Multiple permissions, by name
	$this->restrictWithPermissions(['blog.posts.view', 'blog.posts.manage'], site_url('login') );

## Accessing the Libraries

There may be times when you don't need to hit one of the restrict* methods, but you do need to get access to the 
[Authentication](authentication.md) and [Authorization](authorization.md) libraries directly. You must first call 
the `setupAuthClasses()` method, which is called automatically by the restrict* methods and loads the libraries, 
logs users, etc.

Once that method is ran, you can get direct access to the loaded instance through either `$this->authenticate` 
or `$this->authorize` class vars.

	$this->setupAuthClasses();
	$userId = $this->authenticate->id();
	$good = $this->authorize->hasPermission('blog.posts.view', $userId);
