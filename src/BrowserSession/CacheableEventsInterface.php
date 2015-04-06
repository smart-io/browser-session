<?php
namespace Smart\BrowserSession;

interface CacheableEventsInterface
{
    public function onFetch($object);
}
