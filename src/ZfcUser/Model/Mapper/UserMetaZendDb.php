<?php

namespace ZfcUser\Model\Mapper;

use ZfcBase\Mapper\DbMapperAbstract,
    ZfcUser\Module as ZfcUser,
    ZfcUser\Model\UserMeta as UserMetaModel,
    ZfcUser\Model\Mapper\UserMeta as UserMetaMapper,
    ArrayObject;

class UserMetaZendDb extends DbMapperAbstract implements UserMetaMapper
{
    protected $tableName = 'user_meta';

    public function add(UserMetaModel $userMeta)
    {
        return $this->persist($userMeta);
    }

    public function update(UserMetaModel $userMeta)
    {
        return $this->persist($userMeta, 'update');
    }

    public function get($userId, $metaKey)
    {
        $rowset = $this->getTableGateway()->select(array('user_id' => $userId));
        $row = $rowset->current();
        $userMetaModelClass = ZfcUser::getOption('usermeta_model_class');
        $userMeta = $userMetaModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $userId, 'row' => $row));
        return $userMeta;
    }

    public function persist(UserMetaModel $userMeta, $mode = 'insert')
    {
        $data = new ArrayObject(array(
            'user_id'  => $userMeta->getUser()->getUserId(),
            'meta_key' => $userMeta->getMetaKey(),
            'meta'     => $userMeta->getMetaRaw(),
        ));
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'userMeta' => $userMeta));
        if ('update' === $mode) {
            $this->getTableGateway()->update((array) $data, array('user_id' => $userMeta->getUser()->getUserId(), 'meta_key' => $userMeta->getMetaKey()));
        } elseif ('insert' === $mode) {
            $this->getTableGateway()->insert((array) $data);
        }
        return $userMeta;
    }
}
