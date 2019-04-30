<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class Device
{

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $deviceId;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    protected $deviceName;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime")
     * @Serializer\SerializedName("timestamp")
     */
    protected $registration;

    /**
     * Device constructor.
     *
     * @param string $deviceId
     * @param string $deviceName
     */
    public function __construct(string $deviceId, string $deviceName)
    {
        $deviceIdLen   = strlen($deviceId);
        $deviceNameLen = strlen($deviceName);

        if ($deviceIdLen == 0 || $deviceIdLen > 255) {
            throw new \RuntimeException('device id is mandatory and their maximum length is 255');
        }

        if ($deviceNameLen == 0 || $deviceNameLen > 255) {
            throw new \RuntimeException('device name is mandatory and their maximum length is 255 bytes');
        }

        $this->deviceId   = $deviceId;
        $this->deviceName = $deviceName;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    /**
     * @return string
     */
    public function getDeviceName(): string
    {
        return $this->deviceName;
    }

    /**
     * @return string
     */
    public function getDeviceNameURLEncoded(): string
    {
        return urlencode($this->deviceName);
    }

    /**
     * @return \DateTime|null
     */
    public function getRegistration(): ?\DateTime
    {
        return $this->registration;
    }
}
