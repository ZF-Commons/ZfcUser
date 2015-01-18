<?php
namespace ZfcUser\Authentication\Listener;

use Zend\Session\SessionManager;

class RegenerateSessionIdentifier
{
    /**
     *
     * @var SessionManager
     */
    protected $session;
    
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }
    
    public function __invoke()
    {
        $this->session->regenerateId();
    }
}
