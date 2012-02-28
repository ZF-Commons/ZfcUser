ZfcUser
=======
Version 0.0.1 Created by Evan Coury and the ZF-Commons team

Introduction
------------

ZfcUser is a user registration and authentication module for Zend Framework 2.
Out of the box, ZfcUser works with Zend\Db, however alternative storage adapter
modules are available (see below). ZfcUser provides the foundations for adding
user authentication and registration to your ZF2 site. It is designed to be very
simple and easily to extend.

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
* Optional E-mail address verification [IN PROGRESS]
* Forgot Password [INCOMPLETE]
* Provide ActionController plugin and view helper [INCOMPLETE]

Installation
------------

### Main Setup

1. Install the [ZfcBase](https://github.com/ZF-Commons/ZfcBase) ZF2 module
   by cloning it into `./vendor/` and enabling it in your
   `application.config.php` file.
2. Clone this project into your `./vendor/` directory and enable it in your
   `application.config.php` file.
3. Import the SQL schema located in `./vendor/ZfcUser/data/schema.sql`.
4. Copy `./vendor/ZfcUser/config/module.zfcuser.config.php.dist` to
   `./config/autoload/module.zfcuser.config.php`.

### Post-Install: Doctrine2 ORM

Coming soon...

### Post-Install: Doctrine2 MongoDB ODM

Coming soon...

### Post-Install: Zend\Db

1. If you do not already have a PDO connection set up via DI, put the following
   in `./config/autoload/database.config.php`:

        <?php
        // ./config/autoload/database.config.php
        return array(
            'di' => array(
                'instance' => array(
                    'alias' => array(
                        'masterdb' => 'PDO',
                    ),
                    'masterdb' => array(
                        'parameters' => array(
                            'dsn'            => 'mysql:dbname=CHANGEME;host=CHANGEME',
                            'username'       => 'CHANGEME',
                            'passwd'         => 'CHANGEME',
                            'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
                        ),
                    ),
                ),
            ),
        );

2. Now, specify the DI alias for your PDO connection in
   `./configs/autoload/module.zfcuser.config.php`, under the 'pdo' setting.
   If you created the `./config/autoload/database.config.php` file in the
   previous step, the alias you'll specify is 'masterdb'.

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

The password hash settings may be changed at any time without invalidating
existing user accounts. Existing user passwords will be re-hashed automatically
on their next successful login.

**WARNING:** Changing the default password hash settings can cause serious
problems such as making your hashed passwords more vulnerable to brute force
attacks or making hashing so expesnive that login and registration is
unacceptably slow for users and produces a large burden on your server(s). The
default settings provided are a very reasonable balance between the two,
suitable for computing power in 2011.

Options
-------

The ZfcUser module has some options to allow you to quickly customize the basic
functionality. After installing ZfcUser, copy
`./vendor/ZfcUser/config/module.zfcuser.config.php` to
`./config/autoload/module.config.php` and change the values as desired.

The following options are available:

- **user_model_class** - Name of Entity class to use. Useful for using your own
  entity class instead of the default one provided. Default is
  `ZfcUser\Model\User`.
- **enable_username** - Boolean value, enables username field on the
  registration form, and allows users to log in using their username _OR_ email
  address.  Default is `false`.
- **enable_display_name** - Boolean value, enables a display name field on the
  registration form. Default value is `false`.
- **require_activation** - Boolean value, require that the user verify their
  email address to 'activate' their account. Default value is `false`. (Note,
  this doesn't actually work yet, but defaults an 'active' field in the DB to
  0.)
- **login_after_registration** - Boolean value, automatically logs the user in
  after they successfully register. Default value is `false`.
- **registration_form_captcha** - Boolean value, determines if a captcha should
  be utilized on the user registration form. Default value is `true`. (Note,
  right now this only utilizes a weak Zend\Text\Figlet CAPTCHA, but I have plans
  to make all Zend\Captcha adapters work.)
- **password_hash_algorithm** - Name of the hashing algorithm to use for
  hashing.  Supported algorithms are `blowfish`, `sha512`, and `sha256`. Default
  is `blowfish`.
- **blowfish_cost** - Only used if `password_hash_algorithm` is set to
  `blowfish`. This should be an integer between 4 and 31. The number represents
  the base-2 logarithm of the iteration count used for hashing.  Default is `10`
  (about 10 hashes per second on an i5).
- **sha256_rounds** - Only used if `password_hash_algorithm` is set to `sha256`.
  This should be an integer between 1000 and 999,999,999. The number represents
  the iteration count used for hashing. Default is `5000`.
- **sha512_rounds** - Only used if `password_hash_algorithm` is set to `sha512`.
  This should be an integer between 1000 and 999,999,999. The number represents
  the iteration count used for hashing. Default is `5000`.

Changing Registration Captcha Element
-------------------------------------

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
