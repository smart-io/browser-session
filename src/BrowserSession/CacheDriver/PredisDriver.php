<?php
namespace Sinergi\BrowserSession\CacheDriver;

use Predis\Client;
use Sinergi\BrowserSession\CacheInterface;

class PredisDriver implements CacheDriverInterface, CacheInterface
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param $key
     * @return int
     */
    public function exists($key)
    {
        return $this->getClient()->exists($key);
    }

    /**
     * @param $key
     * @return string
     */
    public function fetch($key)
    {
        return $this->getClient()->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     * @throws \Predis\Response\ServerException
     * @throws \Predis\Transaction\AbortedMultiExecException
     */
    public function store($key, $value)
    {
        $this->getClient()->transaction()->set(
            $key,
            $value
        )->execute();
    }

    /**
     * @param $key
     * @return void
     * @throws \Predis\Response\ServerException
     * @throws \Predis\Transaction\AbortedMultiExecException
     */
    public function delete($key)
    {
        $this->getClient()->transaction()->del([$key])->execute();
    }
}
