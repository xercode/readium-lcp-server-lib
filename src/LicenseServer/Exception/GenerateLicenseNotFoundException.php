<?php

namespace Xercode\Readium\LicenseServer\Exception;

use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Model\PartialLicense;
use Xercode\Readium\Model\ProtectedContent;

class GenerateLicenseNotFoundException extends BadRequestException
{
    /**
     * @var string
     */
    protected $contentId;

    /**
     * @var PartialLicense
     */
    private $partialLicense;

    public function __construct(string $contentId, PartialLicense $partialLicense, string $message = 'Content not found!', \Exception $previous = null)
    {
        parent::__construct($message, $previous);

        $this->contentId = $contentId;
        $this->partialLicense = $partialLicense;
    }

    /**
     * @return string
     */
    public function getContentId(): string
    {
        return $this->contentId;
    }

    /**
     * @return PartialLicense
     */
    public function partialLicense(): PartialLicense
    {
        return $this->partialLicense;
    }
}
