<?php
namespace Smart\BrowserSession\RouterDriver;

use Smart\BrowserSession\Cookie;

interface RouterDriverInterface
{
    /**
     * @param $key
     * @return boolean
     */
    public function cookieExists($key);

    /**
     * @param $key
     * @return Cookie
     */
    public function getCookie($key);

    /**
     * @param Cookie $cookie
     * @return mixed
     */
    public function addCookie(Cookie $cookie);
}
