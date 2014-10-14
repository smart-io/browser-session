<?php
namespace Sinergi\BrowserSession;

use Sinergi\BrowserSession\RouterDriver\RouterDriverInterface;
use Sinergi\BrowserSession\RouterDriver\DatabaseDriverInterface;
use Sinergi\BrowserSession\CacheDriver\CacheDriverInterface;

class BrowserSessionController
{
    /**
     * @var BrowserSessionEntity
     */
    protected $session;

    /**
     * @var array
     */
    protected $flaggedForUpdate = [];

    /**
     * @var DatabaseDriverInterface
     */
    protected $databaseDriver;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var RouterDriverInterface
     */
    protected $routerDriver;

    /**
     * @var CacheDriverInterface
     */
    protected $cacheDriver;

    /**
     * @var BrowserSessionCache
     */
    protected $browserSessionCache;

    /**
     * @var BrowserSessionRepository
     */
    protected $browserSessionRepository;

    /**
     * @param DatabaseDriverInterface $databaseDriver
     * @param RouterDriverInterface $routerDriver
     * @param CacheDriverInterface $cacheDriver
     * @param Config $config
     */
    public function __construct(
        DatabaseDriverInterface $databaseDriver = null,
        RouterDriverInterface $routerDriver = null,
        CacheDriverInterface $cacheDriver = null,
        Config $config = null
    )
    {
        if (null !== $databaseDriver) {
            $this->databaseDriver = $databaseDriver;
        }
        if (null !== $routerDriver) {
            $this->routerDriver = $routerDriver;
        }
        if (null !== $cacheDriver) {
            $this->cacheDriver = $cacheDriver;
        }
        if (null !== $config) {
            $this->config = $config;
        }
    }

    public function __destruct()
    {
        foreach ($this->flaggedForUpdate as $session) {
            $this->updateSession($session);
        }
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->config = new Config();
        }
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return DatabaseDriverInterface
     */
    public function getDatabaseDriver()
    {
        return $this->databaseDriver;
    }

    /**
     * @param DatabaseDriverInterface $databaseDriver
     * @return $this
     */
    public function setDatabaseDriver(DatabaseDriverInterface $databaseDriver)
    {
        $this->databaseDriver = $databaseDriver;
        return $this;
    }

    /**
     * @return RouterDriverInterface
     */
    public function getRouterDriver()
    {
        return $this->routerDriver;
    }

    /**
     * @param RouterDriverInterface $routerDriver
     * @return $this
     */
    public function setRouterDriver(RouterDriverInterface $routerDriver)
    {
        $this->routerDriver = $routerDriver;
        return $this;
    }

    /**
     * @return CacheDriverInterface
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    /**
     * @param CacheDriverInterface $cacheDriver
     * @return $this
     */
    public function setCacheDriver(CacheDriverInterface $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
        return $this;
    }

    /**
     * @param BrowserSessionCache $browserSessionCache
     * @return $this
     */
    public function setBrowserSessionCache(BrowserSessionCache $browserSessionCache)
    {
        $this->browserSessionCache = $browserSessionCache;
        return $this;
    }

    /**
     * @return BrowserSessionCache
     */
    public function getBrowserSessionCache()
    {
        if (null !== $this->browserSessionCache) {
            return $this->browserSessionCache;
        }

        return $this->browserSessionCache = new BrowserSessionCache($this->getCacheDriver());
    }

    /**
     * @param BrowserSessionRepository $browserSessionRepository
     * @return $this
     */
    public function setBrowserSessionRepository(BrowserSessionRepository $browserSessionRepository)
    {
        $this->browserSessionRepository = $browserSessionRepository;
        return $this;
    }

    /**
     * @return BrowserSessionRepository
     */
    public function getBrowserSessionRepository()
    {
        if (null !== $this->browserSessionRepository) {
            return $this->browserSessionRepository;
        }

        return $this->browserSessionCache = $this->databaseDriver->getRepository();
    }

    /**
     * @param BrowserSessionEntity $session
     */
    public function flagSessionForUpdate(BrowserSessionEntity $session)
    {
        $this->flaggedForUpdate[spl_object_hash($session)] = $session;
    }


    /**
     * @return BrowserSessionEntity
     */
    public function createSession()
    {
        $session = new BrowserSessionEntity();
        $this->flagSessionForUpdate($session);
        return $this->session = $session;
    }

    /**
     * @param BrowserSessionEntity $session
     * @return $this
     */
    public function setSession(BrowserSessionEntity $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return null|BrowserSessionEntity
     */
    public function getSession()
    {
        if (null !== $this->session) {
            return $this->session;
        }

        $token = $this->getCookie();
        if ($token) {
            if ($this->getBrowserSessionCache()->exists($token)) {
                $session = $this->getBrowserSessionCache()->fetch($token);
            } else {
                $session = $this->getBrowserSessionRepository()->findOneBy(['token' => $token]);
            }
            return $this->session = $session;
        }

        return null;
    }

    /**
     * @param BrowserSessionEntity $browserSession
     */
    public function updateSession(BrowserSessionEntity $browserSession)
    {
        $browserSession->generateExpirationDate();
        $this->getBrowserSessionCache()->store($browserSession->getToken(), $browserSession);
        if ($this->getDatabaseDriver()->isBackgroundDriver()) {
            $this->getDatabaseDriver()->mergeOrPersistBackground($browserSession);
        } else {
            $this->getDatabaseDriver()->mergeOrPersist($browserSession);
        }
        $this->saveCookie($browserSession);
    }

    /**
     * @return Cookie
     */
    public function getCookie()
    {
        if ($this->getRouterDriver()->cookieExists(BrowserSessionEntity::COOKIE_KEY)) {
            return $this->getRouterDriver()->getCookie(BrowserSessionEntity::COOKIE_KEY);
        }
        return null;
    }

    public function saveCookie(BrowserSessionEntity $browserSession)
    {
        $config = $this->getConfig();

        $cookie = new Cookie();
        $cookie->setName(BrowserSessionEntity::COOKIE_KEY);
        $cookie->setValue($browserSession->getToken());
        $cookie->setExpiration($browserSession->getExpirationDate());
        $cookie->setPath($config->getPath());
        $cookie->setDomain($config->getDomain());
        $cookie->setIsSecure($config->isSecure());
        $cookie->setIsHttpOnly($config->isHttponly());

        $this->getRouterDriver()->addCookie($cookie);
    }
}
