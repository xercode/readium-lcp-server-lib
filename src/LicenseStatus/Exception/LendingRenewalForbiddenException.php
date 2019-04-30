<?php

namespace Xercode\Readium\LicenseStatus\Exception;

use Xercode\Readium\Exception\ForbiddenRequestException;
use Xercode\Readium\Model\Device;

class LendingRenewalForbiddenException extends ForbiddenRequestException
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
        string $message = 'Rejected request!',
        \Exception $previous = null
    ) {
        if ($message == null || empty($message)) {
            $message = sprintf(
                'Incorrect renewal period, your publication %s could not be renewed for device %S.',
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
