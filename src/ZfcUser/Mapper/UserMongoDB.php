<?php

namespace ZfcUser\Mapper;

use Doctrine\ODM\MongoDB\DocumentManager,
    ZfcUser\Module,
    ZfcUser\Model\UserInterface as UserModelInterface,
    ZfcBase\EventManager\EventProvider;

class UserMongoDB extends EventProvider implements UserInterface
{
    protected $dm;

    public function persist(UserModelInterface $user)
    {
        $dm = $this->getDocumentManager();
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('user' => $user, 'em' => $dm));
        $dm->persist($user);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'em' => $dm));
        $dm->flush();
    }

    public function findByEmail($email)
    {
        $dm = $this->getDocumentManager();
        $user = $this->getUserRepository()->findOneBy(array('email' => $email));
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'em' => $dm));
        return $user;
    }

    public function findByUsername($username)
    {
        $dm = $this->getDocumentManager();
        $user = $this->getUserRepository()->findOneBy(array('username' => $username));
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'em' => $dm));
        return $user;
    }
    
    public function findById($id)
    {
        $dm = $this->getDocumentManager();
        $user = $this->getUserRepository()->find($id);
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'em' => $dm));
        return $user;
    }

    public function getDocumentManager()
    {
        return $this->dm;
    }

    public function setDocumentManager(DocumentManager $dm)
    {
        $this->dm = $dm;
        return $this;
    }

    public function getUserRepository()
    {
    	$class = Module::getOption('user_model_class');
        return $this->getDocumentManager()->getRepository($class);
    }

}
