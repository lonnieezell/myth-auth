# Highlighted Changes in Version 

## Unreleased

_No changes yet_

## 1.2.0

Released July 13, 2022

Enhancements:

- Added alternate authorization Models with stronger typing

## 1.1.0

Released July 13, 2022

**It has been more than a year since the previous release. The volume of changes since `1.0.1`**
**is daunting and individual Pull Requests did not keep up with the docs. If you would be willing**
**to help generate this section please [see this issue](https://github.com/lonnieezell/myth-auth/issues/543).**

Visit the [CHANGELOG](https://github.com/lonnieezell/myth-auth/blob/develop/CHANGELOG.md) or
[Release Notes](https://github.com/lonnieezell/myth-auth/releases/tag/v1.1.0) for details.

## 1.0.1

Released July 2, 2021

Enhancements:

- Added Infection for mutation testing (test quality testing)
- Added RoaveBC to detect breaking changes
- Added Tachycardia for slow test detection and annotation
- Added Coveralls to track and record test coverage
- Switched code style enforcement to PHP CS Fixer to match the framework (awaiting configuration)
- Added `FlatAuthorization::usersInGroup()`

Bugs Fixed:

- Fixed a typo causing user activation to fail
- Enforced user field validation to prevent false password validation failures
- Fixed a bug preventing empty cache stores

## 1.0 final

Released June 9, 2021

Breaking Changes:
- Config\Auth::$requireActivation should now be `null` instead of `false`
- Config\Auth::$activeResetter should now be `null` instead of `false`
- `UserModel` now requires `username` to be 30 characters or less (to match the database restriction)

Enhancements:

- All password-related features have been moved to a new class, `Myth\Auth\Password`, and can be accessed statically.
- New Slovak translation
- New German translation
- New Indonesian translation
- New Dutch translation
- New CLI command: auth:activate_user
- New CLI command: auth:hash_password
- New CLI command: auth:set_password
- new Fakers for Groups, Permissions, and Users for use during testing
- AuthController now has a `_render` method you can override if you use a different view renderer, like Twig
- new method: `GroupModel::getGroupsForUser`

Bugs Fixed:

- fixed improper redirect issue where it was using base_url instead of site_url
- permissions cache is now deleted when the groups cached is deleted
- Login filter now ignores the activate account link


## 1.0-beta 3  

Released May 11, 2020

Security Fixes:

The biggest fix this time around was by michalsn that restricts the fields passed during registration
to only those defined in Config\Auth::$validFields and $personalFields, as well as password. This stops
someone from passing in `active=1` in your form and bypassing activation. If you cannot update Myth:Auth
due to core CodeIgniter version requirements, then please adjust your published AuthController as
[shown here](https://github.com/lonnieezell/myth-auth/blob/develop/src/Controllers/AuthController.php#L167).

Enhancements:

- User entity now has `getRoles()` method
- can re-send activation emails manually now.
- Filters have been added to the list of items that can be published
- new Russian translation
- new Brazilian Portuguese translation
- new Italian translation
- adjusted username validation to include some punctuation

Bugs Fixed: 

- Can no longer create multiple groups of the same name.
- Fixed an issue with remember-me cookies not being sent with redirects (requires latest develop branch of CI4)

These are just highlights. See the full changelog for more details. 

