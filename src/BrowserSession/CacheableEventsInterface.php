<?php
namespace Sinergi\BrowserSession;

interface CacheableEventsInterface
{
    public function onFetch($object);
}
