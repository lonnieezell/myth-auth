# Highlighted Changes in Version 

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

