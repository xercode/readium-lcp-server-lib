<?php

namespace Xercode\Readium\Exception;

use Xercode\Readium\Component\HttpStatus;

class UnauthorizedException extends RuntimeException
{
    public function __construct(string $message = 'User or password do not match!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::UNAUTHORIZED, $previous);
    }
}
