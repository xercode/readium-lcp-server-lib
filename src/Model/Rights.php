<?php

namespace Xercode\Readium\Model;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Xercode\Readium\Component\ParameterBag;

class Rights
{
    /**
     * Maximum number of pages that can be printed over the lifetime of the license
     * if null Unlimited
     *
     * @var integer|null
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("print")
     */
    protected $print;

    /**
     * Maximum number of characters that can be copied to the clipboard over the lifetime of the license
     * if null Unlimited
     *
     * @var integer|null
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("copy")
     */
    protected $copy;

    /**
     * Date and time when the license begins
     * if null perpetual license
     *
     * @var DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP'>")
     * @Serializer\SerializedName("start")
     */
    protected $start;

    /**
     * @var DateTime|null
     *
     * if null perpetual license
     *
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP'>")
     * @Serializer\SerializedName("end")
     */
    protected $end;

    /**
     * Custom parameters.
     *
     * @var \Xercode\Readium\Component\ParameterBag |null
     * @Serializer\Type("Xercode\Readium\Component\ParameterBag")
     */
    public $attributes;

    /**
     * Rights constructor.
     *
     * @param int           $print
     * @param int           $copy
     * @param DateTime      $start
     * @param DateTime|null $end
     */
    public function __construct(
        ?int $print = null,
        ?int $copy = null,
        ?DateTime $start = null,
        ?DateTime $end = null,
        array $attributes = array()
    ) {
        $this->print      = $print;
        $this->copy       = $copy;
        $this->start      = $start;
        $this->end        = $end;
        $this->attributes = new ParameterBag($attributes);
    }

    /**
     * @return int|null
     */
    public function getPrint(): ?int
    {
        return $this->print;
    }

    /**
     * @return int|null
     */
    public function getCopy(): ?int
    {
        return $this->copy;
    }

    /**
     * @return DateTime|null
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * @return DateTime|null
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * @return ParameterBag|null
     */
    public function getAttributes(): ?ParameterBag
    {
        return $this->attributes;
    }
}
