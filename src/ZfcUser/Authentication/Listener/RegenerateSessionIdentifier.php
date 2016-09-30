<?php
namespace ZfcUser\Authentication\Listener;

use Zend\Session\SessionManager;

/**
 * Listener which provides session fixation protection by regenerating
 * the SID before an authentication request is handled
 */
class RegenerateSessionIdentifier
{
    /**
     * @var SessionManager
     */
    protected $session;

    /**
     * @param SessionManager $session Session Manager instance
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }
    
    /**
     * Regenerate the SID of the session being managed by the 
     * provided Session Manager
     */
    public function __invoke()
    {
        $this->session->regenerateId();
    }
}
