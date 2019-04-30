<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class Signature
{
    const DEFAULT_ALGORITHM = 'http://www.w3.org/2001/04/xmlenc#sha256';

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("certificate")
     */
    protected $certificate;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("algorithm")
     */
    protected $algorithm;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("value")
     */
    protected $value;

    /**
     * Signature constructor.
     *
     * @param string $certificate
     * @param string $algorithm
     * @param string $value
     */
    public function __construct(string $certificate, string $algorithm, string $value)
    {
        $this->certificate = $certificate;
        $this->algorithm   = $algorithm;
        $this->value       = $value;
    }

    /**
     * @return string
     */
    public function getCertificate(): string
    {
        return $this->certificate;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
