<?php

namespace Xercode\Readium\Model;

use DateTime;
use JMS\Serializer\Annotation as Serializer;


class Timestamps
{
    /**
     * @var DateTime
     *
     * Time and Date when the License Document was last updated.
     *
     * @Serializer\Type("DateTime")
     * @Serializer\SerializedName("license")
     */
    protected $license;

    /**
     * @var DateTime
     *
     * Time and Date when the Status Document was last updated.
     *
     * @Serializer\Type("DateTime")
     * @Serializer\SerializedName("status")
     */
    protected $status;

    /**
     * @return DateTime
     */
    public function getLicense(): DateTime
    {
        return $this->license;
    }

    /**
     * @return DateTime
     */
    public function getStatus(): DateTime
    {
        return $this->status;
    }
}
