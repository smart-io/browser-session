<?php
namespace Sinergi\BrowserSession;

use DateTime;

class Cookie
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var DateTime
     */
    protected $expiration;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var boolean
     */
    protected $isSecure;

    /**
     * @var boolean
     */
    protected $isHttpOnly;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @param DateTime $expiration
     * @return $this
     */
    public function setExpiration(DateTime $expiration)
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @param boolean $isSecure
     * @return $this
     */
    public function setIsSecure($isSecure)
    {
        $this->isSecure = $isSecure;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHttpOnly()
    {
        return $this->isHttpOnly;
    }

    /**
     * @param boolean $isHttpOnly
     * @return $this
     */
    public function setIsHttpOnly($isHttpOnly)
    {
        $this->isHttpOnly = $isHttpOnly;
        return $this;
    }
}
