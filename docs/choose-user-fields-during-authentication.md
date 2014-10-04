## Task
How to specify which fields a user can use as their 'identity' when logging in.

## Solution

The configuration directive `auth_identity_fields` is used to control the fields used to look up user identities stored in ZfcUser.  You can configure this directive (via your `config/autoload/zfcuser.global.php` override file) to one of four possible modes:

1. Authenticate via email address only:
```php
'auth_identity_fields' => array( 'email' ),
```

2. Authenticate via username only:
```php
'auth_identity_fields' => array( 'username' ),
```

3. Authenticate via both methods, with username field checked first:
```php
'auth_identity_fields' => array( 'username', 'email' ),
```

4. Authenticate via both methods, with email address field checked first:
```php
'auth_identity_fields' => array( 'email', 'username' ),
```
