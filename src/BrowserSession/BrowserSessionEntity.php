<?php
namespace Sinergi\BrowserSession;

use DateTime;
use DateInterval;
use Sinergi\Token\StringGenerator;
use Sinergi\BrowserSession\Variable\VariableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="Sinergi\BrowserSession\BrowserSessionRepository")
 * @ORM\Table(
 *   name="browser_session",
 *   uniqueConstraints={@ORM\UniqueConstraint(name="token_index", columns={"token"})}
 * )
 */
class BrowserSessionEntity
{
    const COOKIE_KEY = 'browser_session';
    const TOKEN_LENGHT = 128;
    const EXPIRATION_INTERVAL = 'P1Y';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Type("integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Sinergi\BrowserSession\Variable\VariableEntity",
     *   mappedBy="browserSession",
     *   cascade={"persist", "merge", "detach"},
     *   indexBy="key"
     * )
     * @Serializer\Type("ArrayCollection<Sinergi\BrowserSession\Variable\VariableEntity>")
     * @var VariableEntity[]|ArrayCollection|PersistentCollection
     **/
    private $variables;

    /**
     * @ORM\Column(type="string", length=128, columnDefinition="BINARY(128)"))
     * @Serializer\Type("string")
     * @var string
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", name="expiration_date")
     * @Serializer\Type("DateTime")
     * @var DateTime
     */
    private $expirationDate;

    public function __construct()
    {
        $this->setVariables(new ArrayCollection());
        $this->setToken(StringGenerator::randomAlnum(self::TOKEN_LENGHT));
        $this->generateExpirationDate();
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param DateTime $expirationDate
     * @return $this
     */
    public function setExpirationDate(DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function generateExpirationDate()
    {
        $this->setExpirationDate((new DateTime("now"))->add(new DateInterval(self::EXPIRATION_INTERVAL)));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return VariableEntity[]|ArrayCollection|PersistentCollection
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param VariableEntity[]|ArrayCollection|PersistentCollection $variables
     * @return $this
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;
        return $this;
    }
}
