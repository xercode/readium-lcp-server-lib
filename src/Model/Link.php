<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class Link
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("rel")
     */
    protected $rel;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("href")
     */
    protected $href;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("type")
     */
    protected $type;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("title")
     */
    protected $title;

    /**
     * @var integer|null
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("length")
     */
    protected $length;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("hash")
     */
    protected $hash;

    /**
     * @var boolean|null
     *
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("templated")
     */
    protected $templated;


    /**
     * Link constructor.
     *
     * @param string      $rel
     * @param string      $href
     * @param string|null $type
     * @param string|null $title
     * @param int|null    $length
     * @param string|null $hash
     * @param bool|null   $templated
     */
    public function __construct(
        string $rel,
        string $href,
        ?string $type = null,
        ?string $title = null,
        ?int $length = null,
        ?string $hash = null,
        ?bool $templated = null
    ) {
        $this->rel       = $rel;
        $this->href      = $href;
        $this->type      = $type;
        $this->title     = $title;
        $this->length    = $length;
        $this->hash      = $hash;
        $this->templated = $templated;
    }

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @return bool|null
     */
    public function getTemplated(): ?bool
    {
        return $this->templated;
    }
}
