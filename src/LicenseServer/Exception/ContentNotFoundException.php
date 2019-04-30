<?php

namespace Xercode\Readium\LicenseServer\Exception;

use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Model\ProtectedContent;

class ContentNotFoundException extends BadRequestException
{
    /**
     * @var string
     */
    protected $contentId;

    public function __construct(string $contentId, string $message = 'Bad request!', \Exception $previous = null)
    {
        parent::__construct($message, $previous);

        $this->contentId = $contentId;
    }

    /**
     * @return string
     */
    public function getContentId(): string
    {
        return $this->contentId;
    }
}
