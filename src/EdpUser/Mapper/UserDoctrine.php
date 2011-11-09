<?php

namespace EdpUser\Mapper;

use SpiffyDoctrine\Service\Doctrine,
    SpiffyDoctrine\Authentication\Adapter\DoctrineEntity as DoctrineAuthAdapter,
    EdpUser\ModelBase\UserBase,
    EdpCommon\EventManager\EventProvider,
    EdpUser\Service\User as UserService,
    SpiffyDoctrine\Validator\NoEntityExists;

class UserDoctrine extends EventProvider implements UserInterface
{
    protected $authAdapter;
    protected $doctrine;
    protected $emailValidator;

    public function persist(UserBase $user)
    {
        $em = $this->getEntityManager();
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('user' => $user, 'em' => $em));
        $em->persist($user);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'em' => $em));
        $em->flush();
    }

    public function findByEmail($email)
    {
        $user = $this->getUserRepository()->findOneBy(array('email' => $email));
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user));
        return $user;
    }

    public function findByUsername($username)
    {
        return $this->getUserRepository()->findOneBy(array('username' => $username));
    }

    public function getAuthAdapter($identity, $credential, $identityColumn)
    {
        if (null === $this->authAdapter) {
            $authAdapter = new DoctrineAuthAdapter(
                $this->getEntityManager(),
                UserService::getUserModelClass()
            );
            $this->authAdapter = $authAdapter;
        }
        $this->authAdapter->setIdentity($identity)
                          ->setCredential($credential)
                          ->setIdentityColumn($identityColumn);
        return $this->authAdapter;
    }

    public function getEmailValidator()
    {
        if (null === $this->emailValidator) {
            $this->emailValidator = new NoEntityExists(array(
                'em'     => $this->getEntityManager(),
                'entity' => UserService::getUserModelClass(),
                'field'  => 'email',
            ));
        }
        return $this->emailValidator;
    }

    public function getEntityManager()
    {
        return $this->doctrine->getEntityManager();
    }

    public function setDoctrine(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        return $this;
    }

    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository(UserService::getUserModelClass());
    }

}
