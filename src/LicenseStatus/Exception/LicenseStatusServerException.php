<?php

namespace Xercode\Readium\LicenseStatus\Exception;

use Xercode\Readium\Component\HttpStatus;
use Xercode\Readium\Exception\RuntimeException;

class LicenseStatusServerException extends RuntimeException
{
    public function __construct(string $message = 'Internal server error!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::INTERNAL_SERVER_ERROR, $previous);
    }
}
