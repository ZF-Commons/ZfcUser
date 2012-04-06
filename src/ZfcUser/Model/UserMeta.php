<?php

namespace ZfcUser\Model;

use ZfcBase\Model\ModelAbstract;

/**
 * This concept is shamlessly borrowed from Wordpress.
 * @see http://codex.wordpress.org/Function_Reference/get_user_meta
 * @see http://codex.wordpress.org/Database_Description#Table:_wp_usermeta
 */
class UserMeta extends ModelAbstract implements UserMetaInterface
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $meta_key;

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
     * @param UserInterface $user
     * @return UserMeta
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }
 
    /**
     * Get meta_key.
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }
 
    /**
     * Set meta_key.
     *
     * @param string $metaKey
     * @return UserMeta
     */
    public function setMetaKey($metaKey)
    {
        $this->meta_key = $metaKey;
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
