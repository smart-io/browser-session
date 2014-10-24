<?php
namespace Sinergi\BrowserSession\RouterDriver;

use Router\ResponseCookie;
use Router\Router;
use Sinergi\BrowserSession\Cookie;

class SinergiDriver implements RouterDriverInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $key
     * @return boolean
     */
    public function cookieExists($key)
    {
        return $this->router->getRequest()->cookies()->exists($key);
    }

    /**
     * @param $key
     * @return Cookie
     */
    public function getCookie($key)
    {
        if ($cookieValue = $this->router->getRequest()->cookies()->get($key)) {
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
        $cookie = new ResponseCookie(
            $cookie->getName(),
            $cookie->getValue(),
            $cookie->getExpiration(),
            $cookie->getPath(),
            $cookie->getDomain(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );

        $this->router->getResponse()->cookies()->set(
            $cookie->getName(),
            $cookie
        );
    }
}
