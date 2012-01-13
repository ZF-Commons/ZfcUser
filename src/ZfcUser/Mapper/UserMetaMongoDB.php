<?php

namespace ZfcUser\Mapper;

use Doctrine\ODM\MongoDB\DocumentManager,
    ZfcUser\Module,
    ZfcUser\Model\UserMetaInterface as UserMetaModelInterface,
    ZfcBase\EventManager\EventProvider;

class UserMetaMongoDB extends EventProvider implements UserMetaInterface
{

    public function add(UserMetaModelInterface $userMeta)
    {
        return $this->persist($userMeta);
    }

    public function update(UserMetaModelInterface $userMeta)
    {
        return $this->persist($userMeta);
    }

    public function get($userId, $metaKey)
    {
        $dm = $this->getDocumentManager();
        $userMeta = $this->getUserMetaRepository()->findOneBy(array('user' => $userId, 'metaKey' => $metaKey));
        $this->events()->trigger(__FUNCTION__, $this, array('userMeta' => $userMeta, 'em' => $dm));
        return $userMeta;
    }

    public function persist(UserMetaModelInterface $userMeta)
    {
        $dm = $this->getDocumentManager();
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('userMeta' => $userMeta, 'em' => $dm));
        $userMeta->setUser($dm->merge($userMeta->getUser()));
        $dm->persist($userMeta);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('userMeta' => $userMeta, 'em' => $dm));
        $dm->flush();
    }

    public function getDocumentManager()
    {
        return $this->em;
    }

    public function setDocumentManager(DocumentManager $dm)
    {
        $this->em = $dm;
        return $this;
    }

    public function getUserMetaRepository()
    {
    	$class = Module::getOption('usermeta_model_class');
        return $this->getDocumentManager()->getRepository($class);
    }

}
