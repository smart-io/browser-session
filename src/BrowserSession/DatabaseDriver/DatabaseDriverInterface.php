<?php
namespace Sinergi\BrowserSession\DatabaseDriver;

use Sinergi\BrowserSession\BrowserSessionEntity;
use Doctrine\ORM\EntityManagerInterface;
use Sinergi\BrowserSession\BrowserSessionRepository;

interface DatabaseDriverInterface
{
    /**
     * @return BrowserSessionRepository
     */
    public function getRepository();

    /**
     * @return boolean
     */
    public function isBackgroundDriver();

    /**
     * @param BrowserSessionEntity $browserSession
     */
    public function mergeOrPersistBackground(BrowserSessionEntity $browserSession);

    /**
     * @param BrowserSessionEntity $browserSession
     */
    public function mergeOrPersist(BrowserSessionEntity $browserSession);
}
