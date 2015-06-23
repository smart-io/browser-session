<?php
namespace Smart\BrowserSession\DatabaseDriver;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Smart\BrowserSession\BrowserSessionEntity;
use Smart\BrowserSession\BrowserSessionRepository;

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
     *
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
            "Smart\\BrowserSession\\BrowserSessionEntity"
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
     *
     * @return void
     */
    public function mergeOrPersistBackground(
        BrowserSessionEntity $browserSession
    ) {
        return null;
    }

    /**
     * @param BrowserSessionEntity $browserSession
     */
    public function mergeOrPersist(BrowserSessionEntity $browserSession)
    {

        if (!$browserSession->getId()) {
            $this->persist($browserSession);
        } else {
            $this->merge($browserSession);
        }
    }

    /**
     * @param BrowserSessionEntity $browserSession
     */
    private function persist(BrowserSessionEntity $browserSession)
    {
        try {
            $this->getEntityManager()->persist($browserSession);
            $this->getEntityManager()->flush();

        } catch (Exception $e) {

            //todo : log error

        }
    }

    /**
     * @param BrowserSessionEntity $browserSession
     */
    private function merge(BrowserSessionEntity $browserSession)
    {

        try {
            $this->getEntityManager()->merge($browserSession);
            $this->getEntityManager()->flush();
        } catch (Exception $e) {
            $this->persist($browserSession);
        }
    }
}
