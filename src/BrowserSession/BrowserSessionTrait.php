<?php
namespace Sinergi\BrowserSession;

trait BrowserSessionTrait
{
    private $browserSessionController;

    /**
     * @return BrowserSessionController
     */
    protected function getBrowserSessionController()
    {
        return $this->browserSessionController;
    }

    /**
     * @param BrowserSessionController $browserSessionController
     * @return $this
     */
    protected function setBrowserSessionController(BrowserSessionController $browserSessionController)
    {
        $this->browserSessionController = $browserSessionController;
        return $this;
    }

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
