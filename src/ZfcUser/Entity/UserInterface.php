<?php

namespace ZfcUser\Entity;

interface UserInterface
{

    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword();

    /**
     * Get state.
     *
     * @return int
     */
    public function getState();

}
