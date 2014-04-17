ZfcUser
=======
[![Build Status](https://travis-ci.org/ZF-Commons/ZfcUser.png)](https://travis-ci.org/ZF-Commons/ZfcUser)
[![Code Coverage](https://scrutinizer-ci.com/g/ZF-Commons/ZfcUser/badges/coverage.png?s=7d5932c77bea64a417ac8e3da51dca6da1fcb22e)](https://scrutinizer-ci.com/g/ZF-Commons/ZfcUser/)
[![Latest Stable Version](https://poser.pugx.org/zf-commons/zfc-user/v/stable.png)](https://packagist.org/packages/zf-commons/zfc-user)
[![Latest Unstable Version](https://poser.pugx.org/zf-commons/zfc-user/v/unstable.png)](https://packagist.org/packages/zf-commons/zfc-user)

Created by Evan Coury and the ZF-Commons team

Introduction
------------

ZfcUser is a user registration and authentication module for Zend Framework 2.
Out of the box, ZfcUser works with Zend\Db, however alternative storage adapter
modules are available (see below). ZfcUser provides the foundations for adding
user authentication and registration to your ZF2 site. It is designed to be very
simple and easily to extend.

More information and examples are available on the [ZfcUser Wiki](https://github.com/ZF-Commons/ZfcUser/wiki)

Storage Adapter Modules
-----------------------

By default, ZfcUser ships with support for using Zend\Db for persisting users.
However, by installing an optional alternative storage adapter module, you can
take advantage of other methods of persisting users:

- [ZfcUserDoctrineORM](https://github.com/ZF-Commons/ZfcUserDoctrineORM) - Doctrine2 ORM
- [ZfcUserDoctrineMongoODM](https://github.com/ZF-Commons/ZfcUserDoctrineMongoODM) - Doctrine2 MongoDB ODM

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [ZfcBase](https://github.com/ZF-Commons/ZfcBase) (latest master).

Features / Goals
----------------

* Authenticate via username, email, or both (can opt out of the concept of
  username and use strictly email) [COMPLETE]
* User registration [COMPLETE]
* Forms protected against CSRF [COMPLETE]
* Out-of-the-box support for Doctrine2 _and_ Zend\Db [COMPLETE]
* Registration form protected with CAPTCHA [IN PROGRESS] \(Needs more options\)
* Robust event system to allow for extending [IN PROGRESS]
* Support for additional authentication mechanisms via plugins (Google,
  Facebook, LDAP, etc) [INCOMPLETE]
* Optional E-mail address verification [INCOMPLETE]
* Forgot Password [INCOMPLETE]
* Provide ActionController plugin and view helper [INCOMPLETE]

Installation
------------

### Main Setup

#### By cloning project

1. Install the [ZfcBase](https://github.com/ZF-Commons/ZfcBase) ZF2 module
   by cloning it into `./vendor/`.
2. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project and [ZfcBase](https://github.com/ZF-Commons/ZfcBase) in your composer.json:

    ```json
    "require": {
        "zf-commons/zfc-user": "dev-master"
    }
    ```

2. Now tell composer to download ZfcUser by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'ZfcBase',
            'ZfcUser',
        ),
        // ...
    );
    ```

2. Then Import the SQL schema located in `./vendor/zf-commons/zfc-user/data/schema.sql` (if you installed using the Composer) or in `./vendor/ZfcUser/data/schema.sql`.

### Post-Install: Doctrine2 ORM

Coming soon...

### Post-Install: Doctrine2 MongoDB ODM

Coming soon...

### Post-Install: Zend\Db

1. If you do not already have a valid Zend\Db\Adapter\Adapter in your service
   manager configuration, put the following in `./config/autoload/database.local.php`:

```php
<?php
return array(
    'db' => array(
        'driver'    => 'PdoMysql',
        'hostname'  => 'changeme',
        'database'  => 'changeme',
        'username'  => 'changeme',
        'password'  => 'changeme',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);

```

Navigate to http://yourproject/user and you should land on a login page.

Password Security
-----------------

**DO NOT CHANGE THE PASSWORD HASH SETTINGS FROM THEIR DEFAULTS** unless A) you
have done sufficient research and fully understand exactly what you are
changing, **AND** B) you have a **very** specific reason to deviate from the
default settings.

If you are planning on changing the default password hash settings, please read
the following:

- [PHP Manual: crypt() function](http://php.net/manual/en/function.crypt.php)
- [Securely Storing Passwords in PHP by Adrian Schneider](http://www.syndicatetheory.com/labs/securely-storing-passwords-in-php)

The password hash settings may be changed at any time without invalidating existing
user accounts. Existing user passwords will be re-hashed automatically on their next
successful login.

**WARNING:** Changing the default password hash settings can cause serious
problems such as making your hashed passwords more vulnerable to brute force
attacks or making hashing so expensive that login and registration is
unacceptably slow for users and produces a large burden on your server(s). The
default settings provided are a very reasonable balance between the two,
suitable for computing power in 2013.

Options
-------

The ZfcUser module has some options to allow you to quickly customize the basic
functionality. After installing ZfcUser, copy
`./vendor/zf-commons/zfc-user/config/zfcuser.global.php.dist` to
`./config/autoload/zfcuser.global.php` and change the values as desired.

The following options are available:

- **user_entity_class** - Name of Entity class to use. Useful for using your own
  entity class instead of the default one provided. Default is
  `ZfcUser\Entity\User`.
- **enable_username** - Boolean value, enables username field on the
  registration form. Default is `false`.
- **auth_identity_fields** - Array value, specifies which fields a user can
  use as the 'identity' field when logging in.  Acceptable values: username, email.
- **enable_display_name** - Boolean value, enables a display name field on the
  registration form. Default value is `false`.
- **enable_registration** - Boolean value, Determines if a user should be
  allowed to register. Default value is `true`.
- **login_after_registration** - Boolean value, automatically logs the user in
  after they successfully register. Default value is `false`.
- **use_registration_form_captcha** - Boolean value, determines if a captcha should
  be utilized on the user registration form. Default value is `true`. (Note,
  right now this only utilizes a weak Zend\Text\Figlet CAPTCHA, but I have plans
  to make all Zend\Captcha adapters work.)
- **login_form_timeout** - Integer value, specify the timeout for the CSRF security
  field of the login form in seconds. Default value is 300 seconds.
- **user_form_timeout** - Integer value, specify the timeout for the CSRF security
  field of the registration form in seconds. Default value is 300 seconds.
- **use_redirect_parameter_if_present** - Boolean value, if a redirect GET
  parameter is specified, the user will be redirected to the specified URL if
  authentication is successful (if present, a GET parameter will override the
  login_redirect_route specified below).
- **login_redirect_route** String value, name of a route in the application
  which the user will be redirected to after a successful login.
- **logout_redirect_route** String value, name of a route in the application which
  the user will be redirected to after logging out.
- **password_cost** - This should be an integer between 4 and 31. The number
  represents the base-2 logarithm of the iteration count used for hashing.
  Default is `10` (about 10 hashes per second on an i5).
- **enable_user_state** - Boolean value, enable user state usage. Should user's
  state be used in the registration/login process?
- **default_user_state** - Integer value, default user state upon registration.
  What state user should have upon registration?
- **allowed_login_states** - Array value, states which are allowing user to login.
  When user tries to login, is his/her state one of the following? Include null if
  you want user's with no state to login as well.

Changing Registration Captcha Element
-------------------------------------

**NOTICE** These instructions are currently out of date.

By default, the user registration uses the Figlet captcha engine.  This is
because it's the only one that doesn't require API keys.  It's possible to change
out the captcha engine with DI.  For example, to change to Recaptcha, you would
add this to one of your configuration files (global.config.php,
module.config.php, or a dedicated recaptcha.config.php):

    <?php
    // ./config/autoload/recaptcha.config.php
    return array(
        'di'=> array(
            'instance'=>array(
                'alias'=>array(
                    // OTHER ELEMENTS....
                    'recaptcha_element' => 'Zend\Form\Element\Captcha',
                ),
                'recaptcha_element' => array(
                    'parameters' => array(
                        'spec' => 'captcha',
                        'options'=>array(
                            'label'      => '',
                            'required'   => true,
                            'order'      => 500,
                            'captcha'    => array(
                                'captcha' => 'ReCaptcha',
                                'privkey' => RECAPTCHA_PRIVATE_KEY,
                                'pubkey'  => RECAPTCHA_PUBLIC_KEY,
                            ),
                        ),
                    ),
                ),
                'ZfcUser\Form\Register' => array(
                    'parameters' => array(
                        'captcha_element'=>'recaptcha_element',
                    ),
                ),
            ),
        ),
    );
