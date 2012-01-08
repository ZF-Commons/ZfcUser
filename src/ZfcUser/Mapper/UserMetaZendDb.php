<?php

namespace ZfcUser\Mapper;

use EdpCommon\Mapper\DbMapperAbstract,
    ZfcUser\Module,
    ZfcUser\Model\UserMetaInterface as UserMetaModelInterface,
    ArrayObject;

class UserMetaZendDb extends DbMapperAbstract implements UserMetaInterface
{
    protected $tableName = 'user_meta';

    public function add(UserMetaModelInterface $userMeta)
    {
        return $this->persist($userMeta);
    }

    public function update(UserMetaModelInterface $userMeta)
    {
        return $this->persist($userMeta, 'update');
    }

    public function get($userId, $metaKey)
    {
        $db = $this->getReadAdapter();
        $sql = $db->select()
            ->from($this->getTableName())
            ->where('user_id = ?', $userId)
            ->where('meta_key = ?', $metaKey);
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('query' => $sql));
        $row = $db->fetchRow($sql);
        $userMetaModelClass = Module::getOption('usermeta_model_class');
        $userMeta = $userMetaModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $userId, 'row' => $row));
        return $userMeta;
    }

    public function persist(UserMetaModelInterface $userMeta, $mode = 'insert')
    {
        $data = new ArrayObject(array(
            'user_id'  => $userMeta->getUser()->getUserId(),
            'meta_key' => $userMeta->getMetaKey(),
            'meta'     => $userMeta->getMetaRaw(),
        ));
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'userMeta' => $userMeta));
        $db = $this->getWriteAdapter();
        if ('update' === $mode) {
            $db->update($this->getTableName(), (array) $data, $db->quoteInto('user_id = ? AND ', $userMeta->getUser()->getUserId()) . $db->quoteInto('meta_key = ?', $userMeta->getMetaKey()));
        } elseif ('insert' === $mode) {
            $db->insert($this->getTableName(), (array) $data);
        }
        return $userMeta;
    }
}
