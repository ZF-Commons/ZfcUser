<?php

namespace ZfcUser\Model;

use ZfcBase\Model\ModelAbstract;

/**
 * This concept is shamlessly borrowed from Wordpress.
 * @see http://codex.wordpress.org/Function_Reference/get_user_meta
 * @see http://codex.wordpress.org/Database_Description#Table:_wp_usermeta
 */
class UserMetaBase extends ModelAbstract implements UserMeta
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $metaKey;

    /**
     * @var string
     */
    protected $meta;

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
 
    /**
     * Set user.
     *
     * @param User $user
     * @return UserMeta
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
 
    /**
     * Get metaKey.
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }
 
    /**
     * Set metaKey.
     *
     * @param string $metaKey
     * @return UserMeta
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;
        return $this;
    }
 
    /**
     * Get meta.
     *
     * @return mixed
     */
    public function getMeta()
    {
        return unserialize($this->meta);
    }

    /**
     * Set meta.
     *
     * @param mixed $meta
     * @return UserMeta
     */
    public function setMeta($meta)
    {
        $this->meta = serialize($meta);
        return $this;
    }

    /**
     * Get raw meta string (serialized)
     *
     * @return string
     */
    public function getMetaRaw()
    {
        return $this->meta;
    }
}
