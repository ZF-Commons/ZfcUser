<?php

namespace EdpUser\Model;

use EdpCommon\Model\ModelAbstract;

/**
 * This concept is shamlessly borrowed from Wordpress.
 * @see http://codex.wordpress.org/Function_Reference/get_user_meta
 * @see http://codex.wordpress.org/Database_Description#Table:_wp_usermeta
 */
class UserMeta extends ModelAbstract implements UserMetaInterface
{
    /**
     * @var int
     */
    protected $userMetaId;

    /**
     * @var UserInterface
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
     * Get userMetaId.
     *
     * @return int
     */
    public function getUserMetaId()
    {
        return $this->userMetaId;
    }
 
    /**
     * Set userMetaId.
     *
     * @param int $userMetaId
     * @return UserMetaInterface
     */
    public function setUserMetaId($userMetaId)
    {
        $this->userMetaId = $userMetaId;
        return $this;
    }

    /**
     * Get user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
 
    /**
     * Set user.
     *
     * @param UserInterface $user
     * @return UserMetaInterface
     */
    public function setUser(UserInterface $user)
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
     * @return UserMetaInterface
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
     * @return UserMetaInterface
     */
    public function setMeta($meta)
    {
        $this->meta = serialize($meta);
        return $this;
    }
}
