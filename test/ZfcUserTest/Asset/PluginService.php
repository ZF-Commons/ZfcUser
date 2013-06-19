<?php

namespace ZfcUserTest\Asset;

use ZfcUser\Service\AbstractPluginService;

class PluginService extends AbstractPluginService
{
    protected $allowedPluginInterfaces = array(
        'ZfcUser\Plugin\LoginPluginInterface'
    );
}
