<?php

namespace EdpUser\Mapper;

use SpiffyDoctrine\Service\Doctrine,
    SpiffyDoctrine\Authentication\Adapter\DoctrineEntity as DoctrineAuthAdapter,
    EdpUser\Model\User as UserModel,
    SpiffyDoctrine\Validator\NoEntityExists;

class UserDoctrine implements UserInterface
{
    protected $entityClass = 'EdpUser\Model\User';
    protected $authAdapter;
    protected $doctrine;
    protected $emailValidator;

    public function persist(UserModel $user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function findByEmail($email)
    {
        return $this->getUserRepository()->findOneBy(array('email' => $email));
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
                $this->entityClass
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
                'entity' => $this->entityClass,
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
        return $this->getEntityManager()->getRepository($this->entityClass);
    }

}
