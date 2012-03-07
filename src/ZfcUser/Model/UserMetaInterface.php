<?php

namespace ZfcUser\Model;

interface UserMetaInterface
{
    /**
     * Get user.
     *
     * @return User
     */
    public function getUser();
 
    /**
     * Set user.
     *
     * @param User $user
     * @return UserMeta
     */
    public function setUser(User $user);
 
    /**
     * Get metaKey.
     *
     * @return string
     */
    public function getMetaKey();
 
    /**
     * Set metaKey.
     *
     * @param string $metaKey
     * @return UserMeta
     */
    public function setMetaKey($metaKey);
 
    /**
     * Get meta.
     *
     * @return mixed
     */
    public function getMeta();

    /**
     * Set meta.
     *
     * @param mixed $meta
     * @return UserMeta
     */
    public function setMeta($meta);

    /**
     * Get raw meta string (serialized)
     * 
     * @return string
     */
    public function getMetaRaw();
}
