<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class UserKey
{
    const DEFAULT_ALGORITHM = 'http://www.w3.org/2001/04/xmlenc#sha256';


    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("text_hint")
     */
    protected $textHint;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("hex_value")
     */
    protected $hexValue;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("algorithm")
     */
    protected $algorithm;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("key_check")
     */
    protected $keyCheck;

    /**
     * UserKey constructor.
     *
     * @param string $textHint
     * @param string $hexValue
     * @param string $algorithm
     */
    public function __construct(string $textHint, string $hexValue, string $algorithm = self::DEFAULT_ALGORITHM)
    {
        $this->textHint  = $textHint;
        $this->hexValue  = $hexValue;
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getTextHint(): string
    {
        return $this->textHint;
    }

    /**
     * @return string
     */
    public function getHexValue(): string
    {
        return $this->hexValue;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @return string|null
     */
    public function getKeyCheck(): ?string
    {
        return $this->keyCheck;
    }
}
