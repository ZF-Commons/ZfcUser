EdpUser
=======
Version 0.0.1 Created by Evan Coury

Introduction
------------

**NOTE:** This is a work-in-progress. The features listed may be incomplete or
entirely missing.

EdpUser is a ZF2 module which utilizes Doctrine2. It provides the foundations
for adding user authentication and registration to your ZF2 site. It is built to
be very simple and easily to extend.

Requirements
------------
* Zend Framework 2 (currently depends on commit [a8de089](https://github.com/ralphschindler/zf2/commit/a8de0890e216e9826be3414a818c333e2f307028) from Ralph Schindler's [hotfix/di](https://github.com/ralphschindler/zf2/tree/hotfix/di) branch.
* [SpiffyDoctrine](https://github.com/SpiffyJr/SpiffyDoctrine)

Installation
------------
1. Install [SpiffyDoctrine](https://github.com/SpiffyJr/SpiffyDoctrine) per the [README](https://github.com/SpiffyJr/SpiffyDoctrine/blob/master/README.md)
2. Clone EdpUser into your modules directory:
    1. `cd path/to/project/modules`
    2. `git clone git://github.com/EvanDotPro/EdpUser.git`
3. Enable EdpUser in your `application.config.php` modules array.
4. Create the `user` table in your database via the Doctrine CLI:
    1. `cd path/to/SpiffyDoctrine/bin`
    2. `./doctrine orm:schema:create`
5. Navigate to http://yourproject/user and you should land on a login page.


Features
--------
(Most of these are not complete yet)

* Authenticate via username, email, or both (can opt out of the concept of
  username and use strictly email)
* E-mail address verification (optional)
* Forgot Password
* User Registration
* Robust event system to allow for extending
