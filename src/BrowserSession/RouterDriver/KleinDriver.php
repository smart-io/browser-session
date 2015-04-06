<?php
namespace Smart\BrowserSession\RouterDriver;

use Klein\Klein;
use Klein\ResponseCookie;
use Smart\BrowserSession\Cookie;

class KleinDriver implements RouterDriverInterface
{
    /**
     * @var Klein
     */
    private $klein;

    /**
     * @param Klein $klein
     */
    public function __construct(Klein $klein)
    {
        $this->klein = $klein;
    }

    /**
     * @param $key
     * @return boolean
     */
    public function cookieExists($key)
    {
        return $this->klein->request()->cookies()->exists($key);
    }

    /**
     * @param $key
     * @return Cookie
     */
    public function getCookie($key)
    {
        if ($cookieValue = $this->klein->request()->cookies()->get($key)) {
            $cookie = new Cookie();
            $cookie->setName($key);
            $cookie->setValue($cookieValue);
            return $cookie;
        }
        return null;
    }

    /**
     * @param Cookie $cookie
     * @return mixed
     */
    public function addCookie(Cookie $cookie)
    {
        $kleinCookie = new ResponseCookie(
            $cookie->getName(),
            $cookie->getValue(),
            $cookie->getExpiration(),
            $cookie->getPath(),
            $cookie->getDomain(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );

        $this->klein->response()->cookies()->set(
            $cookie->getName(),
            $kleinCookie
        );
    }
}
