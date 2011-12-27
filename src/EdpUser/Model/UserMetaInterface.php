<?php

namespace EdpUser\Model;

interface UserMetaInterface
{
    /**
     * Get userMetaId.
     *
     * @return int
     */
    public function getUserMetaId();
 
    /**
     * Set userMetaId.
     *
     * @param int $userMetaId
     * @return UserMetaInterface
     */
    public function setUserMetaId($userMetaId);

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
}
