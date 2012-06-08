<?php

namespace ZfcUser\Mapper;

use ArrayObject;
use ZfcBase\Mapper\AbstractDbMapper;
use ZfcUser\Module as ZfcUser;

class UserMeta extends AbstractDbMapper implements UserMetaInterface
{
    protected $tableName = 'user_meta';

    public function get($userId, $metaKey)
    {
        $rowset = $this->getTableGateway()->select(array('user_id' => $userId));
        $row = $rowset->current();
        $userMetaModelClass = $this->getClassName();
        $userMeta = $userMetaModelClass::fromArray($row);
        $this->events()->trigger(__FUNCTION__ . '.post', $this, array('user' => $userId, 'row' => $row));
        return $userMeta;
    }

    public function persist($model)
    {
        $class = $this->getClassName();
        if (!is_object($model) || !$model instanceof $class) {
            throw new Exception\InvalidArgumentException('$model must be an instance of ' . $class);
        }
        $data = new ArrayObject(array(
            'user_id'  => $model->getUser()->getUserId(),
            'meta_key' => $model->getMetaKey(),
            'meta'     => $model->getMetaRaw(),
        ));
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'userMeta' => $model));
        $args = func_get_args();
        $mode = 'insert';
        if (isset($args[1])) {
            $mode = $args[1];
        }
        if ('update' === $mode) {
            $this->getTableGateway()->update(
                (array) $data,
                array('user_id' => $model->getUser()->getUserId(), 'meta_key' => $model->getMetaKey())
            );
        } elseif ('insert' === $mode) {
            $this->getTableGateway()->insert((array) $data);
        } else {
            throw new Exception\InvalidArgumentException('Invalid mode given, must be once of "update" or "delete".');
        }
        return $model;
    }

    public function getPaginatorAdapter(array $params)
    {
        // TODO: Implement getPaginatorAdapter() method.
    }

    public function getModelPrototype()
    {
        // TODO: Implement getModelPrototype() method.
    }

    public function setModelPrototype($modelPrototype)
    {
        // TODO: Implement setModelPrototype() method.
    }

    /**
     * Returns the class name of the object mapped by the data mapper
     *
     * @return string
     */
    public function getClassName()
    {
        return ZfcUser::getOption('usermeta_model_class');
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get primary key
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return null;
    }

    public function find($id)
    {
        throw new Exception\RuntimeException('UserMeta has no primary key');
    }

    protected function fromRow($row)
    {
        // leave empty
    }

    public function remove($model)
    {
        // TODO: Implement remove() method.
    }
}
