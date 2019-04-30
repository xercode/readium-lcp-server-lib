<?php


namespace Xercode\Readium\Exception;


use Xercode\Readium\Component\HttpStatus;

class BadRequestException extends RuntimeException
{

    public function __construct(string $message = 'Bad request!', \Exception $previous = null)
    {
        parent::__construct($message, HttpStatus::BAD_REQUEST, $previous);
    }
}
