<?php

namespace EdpUser\Authentication\Adapter;

use EdpUser\Authentication\AuthEvent,
    EdpUser\Module,
    EdpUser\Mapper\UserInterface as UserMapper,
    EdpUser\Util\Password,
    DateTime;

class Db extends AbstractAdapter
{
    protected $mapper;

    public function authenticate(AuthEvent $e)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $e->setIdentity($storage['identity']);
            return;
        }

        $identity   = $e->getRequest()->post()->get('email'); // change field name to 'identity'
        $credential = $e->getRequest()->post()->get('password'); // change field name to 'credential'

        $userObject = $this->getMapper()->findByEmail($identity);

        if (!$userObject && Module::getOption('enable_username')) {
            // Auth by username
            $userObject = $this->getMapper()->findByUsername($identity);
        }
        if (!$userObject) {
            $this->setSatisfied(false);
            // return redirect response?
            return false; // no identity match
        }

        $credentialHash = $this->hashPassword($credential, $userObject->getPassword());

        if ($credentialHash === $userObject->getPassword()) {
            $e->setIdentity($userObject->getUserId());
            $this->updateUserPasswordHash($userObject, $credential)
                 ->updateUserLastLogin($userObject)
                 ->setSatisfied(true);
            $storage = $this->getStorage()->read();
            $storage['identity'] = $e->getIdentity();
            $this->getStorage()->write($storage);
        } else {
            $this->setSatisfied(false);
            return false;
        }

        // do stuff
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    protected function hashPassword($password, $salt = false)
    {
        return Password::hash($password, $salt ?: $this->getNewSalt());
    }

    protected function updateUserPasswordHash($userObject, $password)
    {
        $newHash = $this->hashPassword($password);
        if ($newHash === $userObject->getPassword()) return $this;

        $userObject->setPassword($newHash);

        $this->getMapper()->persist($userObject);
        return $this;
    }

    protected function getNewSalt()
    {
        $algorithm = strtolower(Module::getOption('password_hash_algorithm'));
        switch ($algorithm) {
            case 'blowfish':
                $cost = Module::getOption('blowfish_cost');
                break;
            case 'sha512':
                $cost = Module::getOption('sha512_rounds');
                break;
            case 'sha256':
                $cost = Module::getOption('sha256_rounds');
                break;
            default:
                throw new \Exception(sprintf(
                    'Unsupported hashing algorithm: %s',
                    $algorithm
                ));
                break;
        }
        return Password::getSalt($algorithm, (int) $cost);
    }

    protected function updateUserLastLogin($userObject)
    {
        $userObject->setLastLogin(new DateTime('now'))
                   ->setLastIp($_SERVER['REMOTE_ADDR']);

        $this->getMapper()->persist($userObject);
        return $this;
    }
}
