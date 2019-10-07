# CLI Commands

**Myth:Auth** provides a few command line tools to make managing users and groups easier.

## Publish

	php spark auth:publish

The `publish` command integrates module components into an existing CodeIgniter 4 project.
When run, you will be prompted about each component (e.g. Migrations, Views, Models, etc.)
if you would like to merge them into your project. Most components will run in-place via
Composer or manual autoloading (see also [Autoloading](https://codeigniter4.github.io/userguide/concepts/autoloader.html)
in the CodeIgniter User Guide) but if you need to change code that cannot be extended then
publishing is the easiest way to do it. `publish` will take care of copying the file into
place and updating namespaces accordingly.

### Arguments

`publish` accepts no arguments.

## Create User

	php spark auth:create_user [username] [email]

The `create_user` command will add a new user record to the database. This command creates
the new user with only the `username` and `email` fields propagated, so the user will be
forced to create a password at first login attempt. `create_user` will prompt for missing
fields but can also accept them directly making it easy to batch load users from a file,
script, or data stream. Users are added through `Myth\Auth\Models\UserModel` and thus are
subject to validation rules and casts.

### Arguments

* `username`: A valid username (alpha-numeric or spaces, minimum length 3)
* `email`: A valid, unique email address

## Create Group

	php spark auth:create_group [name] [description]

The `create_group` command will add a new group record to the database. The `name` and
`description` parameters will both be prompted for if missing. If the new group is successfully
created this command will list all the groups from the database by calling `list_groups`.

### Arguments

* `name`: A valid, unique group name (maximum length 255)
* `description`: **(optional)** A valid description (maximum length 255)

## List Groups

	php spark auth:list_groups

The `list_groups` command displays all the groups in the database in a neatly ordered table.

### Arguments

`list_groups` accepts no arguments.
