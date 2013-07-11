<?php

namespace ZfcUser\Extension;

use Zend\Crypt\Password\Bcrypt;

class Password extends AbstractExtension
{
    const EVENT_VERIFY_PRE  = 'password.verify.pre';
    const EVENT_VERIFY_POST = 'password.verify.post';
    const EVENT_CRYPT_PRE   = 'password.crypt.pre';
    const EVENT_CRYPT_POST  = 'password.crypt.post';

    /**
     * @var Bcrypt
     */
    protected $bcrypt;

    /**
     * @var array
     */
    protected $options = array(
        'cost' => 14,
        'salt' => 'create_your_own_salt!'
    );

    /**
     * @return string
     */
    public function getName()
    {
        return 'password';
    }

    /**
     * @return \Zend\Crypt\Password\Bcrypt
     */
    public function getBcrypt()
    {
        if (!$this->bcrypt) {
            $bcrypt = $this->bcrypt = new Bcrypt();
            $bcrypt->setCost($this->options['cost']);
            $bcrypt->setSalt($this->options['salt']);
        }
        return $this->bcrypt;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verify($password, $hash)
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();
        $event->setParams(array(
            'hash'     => $hash,
            'password' => $password
        ));

        $manager->getEventManager()->trigger(static::EVENT_VERIFY_PRE, $event);

        $bcrypt = $this->getBcrypt();

        $event->setParams(array(
            'hash'     => $hash,
            'password' => $password
        ));
        $manager->getEventManager()->trigger(static::EVENT_VERIFY_POST, $event);

        return $bcrypt->verify($password, $hash);
    }

    /**
     * @param string $value
     * @return string
     */
    public function crypt($value)
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();
        $event->setParam('value', $value);

        $manager->getEventManager()->trigger(static::EVENT_CRYPT_PRE, $event);

        $bcrypt = $this->getBcrypt();
        $value  = $bcrypt->create($value);

        $manager->getEventManager()->trigger(static::EVENT_CRYPT_POST, $event);

        return $value;
    }
}