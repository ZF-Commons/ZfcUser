<?php

namespace ZfcUser\Plugin;

interface LoginPluginInterface
{
    const EVENT_PRE_LOGIN    = 'pre.login';
    const EVENT_POST_LOGIN   = 'post.login';
    const EVENT_PRE_LOGOUT   = 'pre.logout';
    const EVENT_PREPARE_FORM = 'prepare.form';
}