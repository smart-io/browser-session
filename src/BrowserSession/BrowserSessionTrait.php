<?php
namespace Smart\BrowserSession;

trait BrowserSessionTrait
{
    /**
     * @return BrowserSessionController
     * @throws \Exception
     */
    private function tryGetBrowserSessionController()
    {
        $browserSessionController = null;
        if (method_exists($this, 'getContainer')) {
            $container = call_user_func([$this, 'getContainer']);
            if (isset($container['browserSessionController'])) {
                $browserSessionController = $container['browserSessionController'];
            }
        }

        if (null === $browserSessionController && method_exists($this, 'getBrowserSessionController')) {
            $browserSessionController = call_user_func([$this, 'getBrowserSessionController']);
        }

        if ($browserSessionController instanceof BrowserSessionController) {
            return $browserSessionController;
        }

        throw new \Exception('You need to provide the trait a method to get the BrowserSessionController');
    }

    /**
     * @return null|BrowserSessionEntity
     */
    protected function getBrowserSession()
    {
        $browserSessionController = $this->tryGetBrowserSessionController();
        if (!$session = $browserSessionController->getSession()) {
            $session = $browserSessionController->createSession();
        } else {
            $browserSessionController->flagSessionForUpdate($session);
        }
        $browserSessionController->saveCookie($session);
        return $session;
    }

    protected function updateBrowserSession()
    {
        $browserSessionController = $this->tryGetBrowserSessionController();
        $session = $this->getBrowserSession();
        $browserSessionController->updateSession($session);
    }

    protected function flagBrowserSessionForUpdate()
    {
        $browserSessionController = $this->tryGetBrowserSessionController();
        $session = $this->getBrowserSession();
        $browserSessionController->flagSessionForUpdate($session);
    }
}
