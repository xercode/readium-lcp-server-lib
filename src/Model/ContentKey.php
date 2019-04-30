<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class ContentKey
{
    const DEFAULT_ALGORITHM = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("encrypted_value")
     */
    protected $encryptedValue;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("algorithm")
     */
    protected $algorithm;

    /**
     * ContentKey constructor.
     *
     * @param string $encryptedValue
     * @param string $algorithm
     */
    public function __construct(string $encryptedValue, string $algorithm = self::DEFAULT_ALGORITHM)
    {
        $this->encryptedValue = $encryptedValue;
        $this->algorithm      = $algorithm;
    }

    /**
     * @return string
     */
    public function getEncryptedValue(): string
    {
        return $this->encryptedValue;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }
}
