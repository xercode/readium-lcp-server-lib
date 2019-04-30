<?php

namespace Xercode\Readium\LicenseServer\Exception;

use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Model\ProtectedContent;

class ContentBadRequestException extends BadRequestException
{
    /**
     * @var ProtectedContent
     */
    protected $content;

    public function __construct(ProtectedContent $content, string $message = 'Bad request!', \Exception $previous = null)
    {
        parent::__construct($message, $previous);

        $this->content = $content;
    }

    /**
     * @return ProtectedContent
     */
    public function protectedContent(): ProtectedContent
    {
        return $this->content;
    }
}
