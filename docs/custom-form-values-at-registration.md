Ref, using ZF 2.1.4 at the time this was written

A bit of a follow up to:
https://github.com/ZF-Commons/ZfcUser/wiki/How-to-embed-the-login-form-on-another-page

In your bootstrap event (hopefully in a custom Module for your user entities and roles and so forth), add this block to your onBootstrap code:



```php
<?php
public function onBootstrap( MVCEvent $e )
{
    $eventManager = $e->getApplication()->getEventManager();
    $em           = $eventManager->getSharedManager();

    // ...
 
    $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

    // To validate new field
    $em->attach('ZfcUser\Form\RegisterFilter','init', function($e) {
        $filter = $e->getTarget();
        $filter->add(array(
            'name'       => 'favorite_icecream',
            'required'   => true,
            'allowEmpty' => false,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                )
            ),
        ));
    });

    // Store the field
    $zfcServiceEvents->attach('register', function($e) {
        $form = $e->getParam('form');
        $user = $e->getParam('user');
            
        /* @var $user \FooUser\Entity\User */
        $user->setUsername( $form->get('username')->getValue() );
        $user->setFavoriteIceCream( $form->get('favorite_icecream')->getValue() );
    });
}
```
