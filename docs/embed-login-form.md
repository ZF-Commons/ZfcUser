## Task
Embed the login form on another page (ie: homepage login widget)

## Solution
ZfcUser provides a View Helper ([zfcUserLoginWidget](https://github.com/ZF-Commons/ZfcUser/blob/master/src/ZfcUser/View/Helper/ZfcUserLoginWidget.php)) which you can use from any view script in your application.  Just add the following call to the location in your markup where you want the form to be rendered:

```php
<?php echo $this->zfcUserLoginWidget(); ?>
```

### Note
The view helper can also __return__ the login form:

```php
<?php $form = $this->zfcUserLoginWidget(array('render' => false)); ?>
```

This will return an object of type [Login](https://github.com/ZF-Commons/ZfcUser/blob/master/src/ZfcUser/Form/Login.php) that can be used to generate a custom login form.
