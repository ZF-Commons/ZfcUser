## Task
Override the built-in view scripts for pages such as registration and sign-in with your own custom view scripts

## Solution

1. In your module, under the `view` directory, create the folder tree `zfc-user/user`
2. Create the necessary override view scripts, depending on which page(s) you want to change:
    * User Login page: `zfc-user/user/login.phtml`
    * User Registration page: `zfc-user/user/register.phtml`
    * Default post-login landing page: `zfc-user/user/index.phtml`
3. Put this into your `module.config.php` file

```php
'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
    ),
```

Refer to each [built-in view script](https://github.com/ZF-Commons/ZfcUser/tree/master/view/zfc-user/user) to see how the form is configured and rendered.

NOTE: Your module must be loaded after ZfcUser or the overriding will not work.  To do this, place your module after ZfcUser in the `modules` key of your application configuration (`config/application.config.php`), or do the following:

```php
<?php
namespace Foo;

class Module
{
    public function init($moduleManager)
    {
        $moduleManager->loadModule('ZfcUser');
    }
}
```

NOTE: As of Zend Framework RC1 it is no longer needed to clone the `ModuleEvent` in the `init()` method.
