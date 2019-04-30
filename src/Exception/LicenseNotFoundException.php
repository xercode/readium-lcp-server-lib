<?php

namespace Xercode\Readium\Exception;


class LicenseNotFoundException extends NotFoundException
{
    /**
     * @var string
     */
    private $licenseId;

    public function __construct(string $licenseId, string $message = 'Not found!', \Exception $previous = null)
    {
        parent::__construct($message, $previous);

        $this->licenseId = $licenseId;
    }

    /**
     * @return string
     */
    public function getLicenseId(): string
    {
        return $this->licenseId;
    }
}
