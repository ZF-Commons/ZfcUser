## Task
Modify the form objects used by ZfcUser for registration and/or login. 

## Solution
You can accomplish this by hooking into the `init` event of the form you wish to modify.  The simplest way to do this is pull the shared event manager and attach a listener.

```php
<?php
public function onBootstrap($e)
{
    $events = $e->getApplication()->getEventManager()->getSharedManager();
    $events->attach('ZfcUser\Form\Register','init', function($e) {
        $form = $e->getTarget();
        // Do what you please with the form instance ($form)
    });
    $events->attach('ZfcUser\Form\RegisterFilter','init', function($e) {
        $filter = $e->getTarget();
        // Do what you please with the filter instance ($filter)
    });
}
```

TODO: Illustrate a method that doesn't use StaticEventManager
