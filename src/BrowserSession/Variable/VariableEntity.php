<?php
namespace Smart\BrowserSession\Variable;

use Smart\BrowserSession\BrowserSessionEntity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerBuilder;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="browser_session_variable",
 *   indexes={
 *     @ORM\Index(name="browser_session_id_and_key_index", columns={"browser_session_id", "key"}),
 *     @ORM\Index(name="key_index", columns={"key"})
 *   }
 * )
 */
class VariableEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Type("integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Smart\BrowserSession\BrowserSessionEntity", inversedBy="variables")
     * @ORM\JoinColumn(name="browser_session_id", referencedColumnName="id", onDelete="CASCADE")
     * @var BrowserSessionEntity
     */
    private $browserSession;

    /**
     * @ORM\Column(type="string", name="`key`", length=70)
     * @Serializer\Type("string")
     * @var string
     */
    private $key;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Type("string")
     * @var string|null
     */
    private $value = null;

    /**
     * @Serializer\Exclude
     * @var mixed
     */
    private $unserializedValue = null;

    /**
     * @ORM\Column(type="string", name="`serialized_type`", length=255)
     * @Serializer\Type("string")
     * @var string
     */
    private $serializedType = null;

    /**
     * @ORM\Column(type="boolean", name="is_serialized")
     * @Serializer\Type("boolean")
     * @var boolean
     */
    private $isSerialized = false;

    /**
     * @return BrowserSessionEntity
     */
    public function getBrowserSession()
    {
        return $this->browserSession;
    }

    /**
     * @param BrowserSessionEntity $browserSession
     *
     * @return $this
     */
    public function setBrowserSession(BrowserSessionEntity $browserSession)
    {
        $this->browserSession = $browserSession;

        return $this;
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
     *
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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSerialized()
    {
        return $this->isSerialized;
    }

    /**
     * @param boolean $isSerialized
     *
     * @return $this
     */
    public function setIsSerialized($isSerialized)
    {
        $this->isSerialized = $isSerialized;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerializedType()
    {
        return $this->serializedType;
    }

    /**
     * @param string $serializedType
     *
     * @return $this
     */
    public function setSerializedType($serializedType)
    {
        $this->serializedType = $serializedType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (null !== $this->unserializedValue) {
            return $this->unserializedValue;
        } elseif ($this->isSerialized) {
            return $this->unserializeValue($this->value);
        }

        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->unserializedValue = $value;
        if (!is_string($value)) {
            $this->serializeValue($value);
        }
        $this->value = $value;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private function serializeValue($value)
    {
        $this->isSerialized = true;
        $serializer = SerializerBuilder::create()->build();
        if (is_object($value)) {
            $this->setSerializedType(get_class($value));
        } else {
            $this->setSerializedType(gettype($value));
        }

        return $serializer->serialize($value, 'json');
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    private function unserializeValue($value)
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->deserialize(
            $value,
            $this->getSerializedType(),
            'json'
        );
    }
}
