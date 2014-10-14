<?php
namespace Sinergi\BrowserSession\RouterDriver;

use Klein\Klein;
use Sinergi\BrowserSession\Cookie;

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
        $this->klein->response()->cookie(
            $cookie->getName(),
            $cookie->getValue(),
            $cookie->getExpiration()->format('Y-m-d H:i:s'),
            $cookie->getPath(),
            $cookie->getDomain(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );
    }
}
