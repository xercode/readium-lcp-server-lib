<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class Event
{
    /**
     * Signals a successful registration event by a device.
     */
    const register = 'register';

    /**
     * Signals a successful renew event.
     */
    const renew = 'renew';

    /**
     * Signals a successful return event.
     */
    const return = 'return';

    /**
     * Signals a revocation event.
     */
    const revoke = 'revoke';

    /**
     * Signals a cancellation event.
     */
    const cancel = 'cancel';

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $deviceId;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("type")
     */
    protected $type;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    protected $deviceName;

    /**
     * @var string
     * @Serializer\Type("DateTime")
     * @Serializer\SerializedName("timestamp")
     */
    protected $timestamp;

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
    public function getType(): string
    {
        return $this->type;
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
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
}
