<?php
namespace Sinergi\BrowserSession;

interface CacheInterface
{
    public function exists($key);
    public function fetch($key);
    public function store($key, $value);
    public function delete($key);
}
