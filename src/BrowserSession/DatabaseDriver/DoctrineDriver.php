<?php
namespace Sinergi\BrowserSession\DatabaseDriver;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Sinergi\BrowserSession\BrowserSessionEntity;
use Sinergi\BrowserSession\BrowserSessionRepository;

class DoctrineDriver implements DatabaseDriverInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityManagerInterface
     * @throws Exception
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return BrowserSessionRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository(
            "Sinergi\\BrowserSession\\BrowserSessionEntity"
        );
    }

    /**
     * @return boolean
     */
    public function isBackgroundDriver()
    {
        return false;
    }

    /**
     * @param BrowserSessionEntity $browserSession
     * @return void
     */
    public function mergeOrPersistBackground(BrowserSessionEntity $browserSession)
    {
        return null;
    }

    /**
     * @param BrowserSessionEntity $browserSession
     */
    public function mergeOrPersist(BrowserSessionEntity $browserSession)
    {
        $this->getEntityManager()->persist($browserSession);
        $this->getEntityManager()->flush($browserSession);
    }
}
