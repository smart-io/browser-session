<?php
namespace Sinergi\BrowserSession;

use JMS\Serializer\SerializerBuilder;
use Sinergi\BrowserSession\CacheDriver\CacheDriverInterface;

abstract class AbstractCacheable implements CacheableInterface
{
    /**
     * @return string
     */
    abstract public function getCacheKey();

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var CacheDriverInterface
     */
    protected $cacheDriver;

    /**
     * @param CacheDriverInterface $cacheDriver
     */
    public function __construct(CacheDriverInterface $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        if ($retval = apc_exists($this->getCacheKey() . $key)) {
            return $retval;
        }
        return $this->cacheDriver->exists($this->getCacheKey() . $key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function fetch($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        if (!$object = apc_fetch($this->getCacheKey() . $key)) {
            $object = $this->cacheDriver->fetch($this->getCacheKey() . $key);
        }

        if ($object) {
            $serializer = SerializerBuilder::create()->build();
            $object = $serializer->deserialize(
                $object,
                $this->getType(),
                'json'
            );

            if ($this instanceof CacheableEventsInterface) {
                $object = $this->onFetch($object);
            }

            return $this->items[$key] = $object;
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function store($key, $value)
    {
        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($value, 'json');
        apc_store($this->getCacheKey() . $key, $jsonContent);
        $this->cacheDriver->store(
            $this->getCacheKey() . $key,
            $jsonContent
        );
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        apc_delete($this->getCacheKey() . $key);
        $this->cacheDriver->delete($this->getCacheKey() . $key);
    }
}
