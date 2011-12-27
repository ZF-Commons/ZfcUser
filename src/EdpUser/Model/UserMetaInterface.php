<?php

namespace EdpUser\Model;

interface UserMetaInterface
{
    /**
     * Get user.
     *
     * @return UserInterface
     */
    public function getUser();
 
    /**
     * Set user.
     *
     * @param UserInterface $user
     * @return UserMetaInterface
     */
    public function setUser(UserInterface $user);
 
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
     * @return UserMetaInterface
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
     * @return UserMetaInterface
     */
    public function setMeta($meta);

    /**
     * Get raw meta string (serialized)
     * 
     * @return string
     */
    public function getMetaRaw();
}
