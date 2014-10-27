<?php
namespace Sinergi\BrowserSession\RouterDriver;

use Router\Request;
use Router\Response;
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
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Router $router
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Router $router, Request $request, Response $response)
    {
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $key
     * @return boolean
     */
    public function cookieExists($key)
    {
        return $this->request->cookies()->exists($key);
    }

    /**
     * @param $key
     * @return Cookie
     */
    public function getCookie($key)
    {
        if ($cookieValue = $this->request->cookies()->get($key)) {
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

        $this->response->cookies()->set(
            $cookie->getName(),
            $cookie
        );
    }
}
