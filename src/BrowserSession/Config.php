<?php
namespace Smart\BrowserSession;

class Config
{
    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var string
     */
    private $domain = null;

    /**
     * @var boolean
     */
    private $isSecure = false;

    /**
     * @var boolean
     */
    private $isHttponly = false;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = null)
    {
        if (null !== $attributes) {
            $this->set($attributes);
        }
    }

    /**
     * @param array $attributes
     */
    public function set(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'path':
                    $this->setPath($value);
                    break;
                case 'domain':
                    $this->setDomain($value);
                    break;
                case 'isSecure':
                    $this->setIsSecure($value);
                    break;
                case 'isHttponly':
                    $this->setIsHttponly($value);
                    break;
            }
        }
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
    public function isHttponly()
    {
        return $this->isHttponly;
    }

    /**
     * @param boolean $isHttponly
     * @return $this
     */
    public function setIsHttponly($isHttponly)
    {
        $this->isHttponly = $isHttponly;
        return $this;
    }
}
