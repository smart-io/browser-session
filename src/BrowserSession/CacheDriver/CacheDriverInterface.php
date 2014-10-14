<?php
namespace Sinergi\BrowserSession\CacheDriver;

interface CacheDriverInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function exists($key);

    /**
     * @param $key
     * @return mixed
     */
    public function fetch($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function store($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key);
}
