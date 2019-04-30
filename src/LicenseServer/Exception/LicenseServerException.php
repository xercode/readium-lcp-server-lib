<?php

namespace Xercode\Readium\LicenseServer\Exception;

use Xercode\Readium\Component\HttpStatus;
use Xercode\Readium\Exception\RuntimeException;

class LicenseServerException extends RuntimeException
{
    public function __construct(string $message = 'Internal server error!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::INTERNAL_SERVER_ERROR, $previous);
    }
}
