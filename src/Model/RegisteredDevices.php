<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class RegisteredDevices
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $licenseId;

    /**
     * @var Device[]
     *
     * @Serializer\Type("array<Xercode\Readium\Model\Device>")
     * @Serializer\SerializedName("devices")
     */
    protected $devices;

    /**
     * @return string
     */
    public function getLicenseId(): string
    {
        return $this->licenseId;
    }

    /**
     * @return Device[]
     */
    public function getDevices(): array
    {
        return $this->devices;
    }

}
