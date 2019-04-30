<?php

namespace Xercode\Readium\LicenseStatus\Exception;

use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Model\Device;

class LendingRenewalBadRequestException extends BadRequestException
{
    /**
     * @var string
     */
    protected $licenseId;

    /**
     * @var Device
     */
    protected $device;


    public function __construct(
        string $licenseId,
        Device $device,
        string $message = 'Bad request!',
        \Exception $previous = null
    ) {
        if ($message == null || empty($message)) {
            $message = sprintf(
                'Your publication %s could not be renewed properly from %s.',
                $licenseId,
                $device->getDeviceId()
            );
        }

        $this->licenseId = $licenseId;
        $this->device    = $device;

        parent::__construct($message, $previous);
    }

    /**
     * @return string
     */
    public function licenseId(): string
    {
        return $this->licenseId;
    }

    /**
     * @return Device
     */
    public function device(): Device
    {
        return $this->device;
    }
}

