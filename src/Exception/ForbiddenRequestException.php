<?php

namespace Xercode\Readium\Exception;

use Xercode\Readium\Component\HttpStatus;

class ForbiddenRequestException extends RuntimeException
{
    public function __construct(string $message = 'Rejected request!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::FORBIDDEN, $previous);
    }
}
