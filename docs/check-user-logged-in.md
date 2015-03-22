## Task
Check if the user is logged in (ie: user identity widget)

## Solution
There are three ways.

### View
ZfcUser provides a View Helper ([zfcUserIdentity](https://github.com/ZF-Commons/ZfcUser/blob/master/src/ZfcUser/View/Helper/ZfcUserIdentity.php)) which you can use from any view script in your application.

```php
<!-- Test if the User is connected -->
<?php if(!$this->zfcUserIdentity()): ?>
    <!-- display the login form -->
    <?php echo $this->zfcUserLoginWidget(array('redirect'=>'application')); ?>
<?php else: ?>
    <!-- display the 'display name' of the user -->
    <?php echo $this->zfcUserIdentity()->getDisplayname(); ?>
<?php endif?>
```

You can also get user's fields (if the user is logged in), like email:

```php
<?php echo $this->zfcUserIdentity()->getEmail(); ?>
```

### Controller

ZfcUser provides a Controller Plugin ([zfcUserAuthentication](https://github.com/ZF-Commons/ZfcUser/blob/master/src/ZfcUser/Controller/Plugin/ZfcUserAuthentication.php)) which you can use from any controller in your application. You can check if the user is connected and get his data:

```php
<?php
if ($this->zfcUserAuthentication()->hasIdentity()) {
    //get the email of the user
    echo $this->zfcUserAuthentication()->getIdentity()->getEmail();
    //get the user_id of the user
    echo $this->zfcUserAuthentication()->getIdentity()->getId();
    //get the username of the user
    echo $this->zfcUserAuthentication()->getIdentity()->getUsername();
    //get the display name of the user
    echo $this->zfcUserAuthentication()->getIdentity()->getDisplayname();
}
?>
```

The controller may also return the Authentication Service :

```php
$authService = $this->zfcUserIdentity()->getAuthService();
```

### Service Manager

```php
<?php
$sm = $app->getServiceManager();
$auth = $sm->get('zfcuser_auth_service');
if ($auth->hasIdentity()) {
    echo $auth->getIdentity()->getEmail();
}
?>
```


