<?php
namespace Smart\BrowserSession\DatabaseDriver;

use Smart\BrowserSession\BrowserSessionEntity;
use Doctrine\ORM\EntityManagerInterface;
use Smart\BrowserSession\BrowserSessionRepository;

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
