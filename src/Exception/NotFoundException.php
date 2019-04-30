<?php


namespace Xercode\Readium\Exception;


use Xercode\Readium\Component\HttpStatus;

class NotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Not found!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::NOT_FOUND, $previous);
    }
}
