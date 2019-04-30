<?php

namespace Xercode\Readium\LicenseStatus\Exception;

use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Model\Device;

class LendingCancelBadRequestException extends BadRequestException
{
    /**
     * @var string
     */
    protected $licenseId;


    public function __construct(string $licenseId, string $message = 'Bad request!', \Exception $previous = null)
    {
        if ($message == null || empty($message)) {
            $message = sprintf('Your publication %s could not be canceled properly.', $licenseId);
        }

        $this->licenseId = $licenseId;

        parent::__construct($message, $previous);
    }

    /**
     * @return string
     */
    public function licenseId(): string
    {
        return $this->licenseId;
    }
}

