<?php
namespace Sinergi\BrowserSession;

trait BrowserSessionHelperTrait
{
    /**
     * @return BrowserSessionController
     */
    abstract function getBrowserSessionController();

    /**
     * @return null|BrowserSessionEntity
     */
    protected function getBrowserSession()
    {
        if (!$session = $this->getBrowserSessionController()->getSession()) {
            $session = $this->getBrowserSessionController()->createSession();
        } else {
            $this->getBrowserSessionController()->flagSessionForUpdate($session);
        }
        $this->getBrowserSessionController()->saveCookie($session);
        return $session;
    }

    protected function updateBrowserSession()
    {
        $session = $this->getBrowserSession();
        $this->getBrowserSessionController()->updateSession($session);
    }

    protected function flagBrowserSessionForUpdate()
    {
        $session = $this->getBrowserSession();
        $this->getBrowserSessionController()->flagSessionForUpdate($session);
    }
}
