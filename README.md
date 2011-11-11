EdpUser
=======
Version 0.0.1 Created by Evan Coury

Introduction
------------

**NOTE:** This is a work-in-progress. The features listed may be incomplete or
entirely missing.

EdpUser is a user registration and authentication module for Zend Framework 2,
which can utilize either Doctrine2 or Zend\Db. It provides the foundations
for adding user authentication and registration to your ZF2 site. It is built to
be very simple and easily to extend.

Requirements
------------

* Zend Framework 2
* [EdpCommon](https://github.com/EvanDotPro/EdpCommon) (latest master).
* [SpiffyDoctrine](https://github.com/SpiffyJr/SpiffyDoctrine) (optional).

Installation (Doctrine2)
------------

1. Install the [EdpCommon](https://github.com/EvanDotPro/EdpCommon) ZF2 module.
    1. `cd path/to/project/modules`
    2. `git clone git://github.com/EvanDotPro/EdpCommon.git`
    3. Enable EdpCommon in your `application.config.php` modules array.
2. Install and configure the [SpiffyDoctrine](https://github.com/SpiffyJr/SpiffyDoctrine) ZF2 
   module per the [installation instructions](https://github.com/SpiffyJr/SpiffyDoctrine/blob/master/docs/INSTALL.md).
3. Install the [EdpUser](https://github.com/EvanDotPro/EdpUser) ZF2 module.
    1. `cd path/to/project/modules`
    2. `git clone git://github.com/EvanDotPro/EdpUser.git`
    3. Enable EdpUser in your `application.config.php` modules array.
4. Create the `user` table in your database via the Doctrine CLI:
    1. `cd path/to/SpiffyDoctrine/bin`
    2. `./doctrine orm:schema:update --dump-sql` (Import the schema into your DB)
    
Navigate to http://yourproject/user and you should land on a login page.

Installation (Zend\Db)
------------

1. Install the [EdpCommon](https://github.com/EvanDotPro/EdpCommon) ZF2 module.
    1. `cd path/to/project/modules`
    2. `git clone git://github.com/EvanDotPro/EdpCommon.git`
    3. Enable EdpCommon in your `application.config.php` modules array.
2. Install the [EdpUser](https://github.com/EvanDotPro/EdpUser) ZF2 module.
    1. `cd path/to/project/modules`
    2. `git clone git://github.com/EvanDotPro/EdpUser.git`
    3. Enable EdpUser in your `application.config.php` modules array.
3. Create the `user` table in your database. (The chema is located in
   `./EdpUser/data/schema.sql`)
4. Add the following to your `Application/configs/module.config.php`:

        // Application/configs/module.config.php
        array(
            'di' => array(
                'instance' => array(
                    'alias' => array(
                        'masterdb'            => 'PDO',
                        'edpuser-pdo'         => 'masterdb',
                        'edpuser-user-mapper' => 'EdpUser\Mapper\UserZendDb',
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

Navigate to http://yourproject/user and you should land on a login page.

Common Use-Cases
----------------

### Checking if a user is logged in from an ActionController
    
    if ($this->getLocator()->get('edpuser-user-service')->getAuthService()->hasIdentity()) {
        //...
    }

### Retrieving a user's identity from an ActionController
    
    $user = $this->getLocator()->get('edpuser-user-service')->getAuthService()->getIdentity();
    return array('user' => $user);

**Note:** `getIdentity()` returns an instance of `EdpUser\Entity\User`.

### Logging a user out from an ActionController

    $this->getLocator()->get('edpuser-user-service')->getAuthService()->clearIdentity();


Features / Goals
----------------

* Authenticate via username, email, or both (can opt out of the concept of 
  username and use strictly email) [COMPLETE]
* User registration [COMPLETE]
* Out-of-the-box support for Doctrine2 _and_ Zend\Db [IN PROGRESS]
* Robust event system to allow for extending [IN PROGRESS]
* Support for additional authentication mechanisms via plugins (Google,
  Facebook, LDAP, etc) [INCOMPLETE]
* Optional E-mail address verification [INCOMPLETE]
* Forgot Password [INCOMPLETE]
