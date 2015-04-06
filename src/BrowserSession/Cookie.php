<?php
namespace Smart\BrowserSession;

use DateTime;
use Serializable;
use JsonSerializable;

class Cookie implements Serializable, JsonSerializable
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
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'expiration' => $this->getExpiration(),
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'isSecure' => $this->isSecure(),
            'isHttpOnly' => $this->isHttpOnly(),
        ];
    }

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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $this->setName($data['name']);
        $this->setValue($data['value']);
        $this->setExpiration($data['expiration']);
        $this->setPath($data['path']);
        $this->setDomain($data['domain']);
        $this->setIsSecure($data['isSecure']);
        $this->setIsHttpOnly($data['isHttpOnly']);
    }

}
