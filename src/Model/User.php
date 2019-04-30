<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;
use Xercode\Readium\Component\ParameterBag;

class User
{
    const DEFAULT_ENCRYPTED = ['email'];
    const VALID_ENCRYPTED = ['email', 'name'];

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("email")
     */
    protected $email;


    /**
     * The lis of fields encrypted
     *
     * @var array|null
     *
     * @Serializer\Type("array")
     * @Serializer\SerializedName("encrypted")
     */
    protected $encrypted;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    protected $name;

    /**
     * Custom parameters.
     *
     * @var \Xercode\Readium\Component\ParameterBag
     * @Serializer\Type("Xercode\Readium\Component\ParameterBag")
     */
    public $attributes;

    /**
     * User constructor.
     *
     * @param string     $email
     * @param array|null $encrypted
     * @param string     $id
     */
    public function __construct(
        string $email,
        ?array $encrypted = self::DEFAULT_ENCRYPTED,
        ?string $id = null,
        ?string $name = null,
        array $attributes = array()
    ) {
        $this->email = $email;
        foreach ($encrypted as $value) {
            if (!in_array($value, self::VALID_ENCRYPTED)) {
                throw new \RuntimeException(sprintf('The value %s is not valid encripted field'));
            }
        }

        $this->encrypted  = $encrypted;
        $this->id         = $id;
        $this->name       = $name;
        $this->attributes = new ParameterBag($attributes);
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array|null
     */
    public function getEncrypted(): ?array
    {
        return $this->encrypted;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ParameterBag
     */
    public function getAttributes(): ?ParameterBag
    {
        if ($this->attributes == null) {
            return null;
        }

        return $this->attributes->all();
    }
}
