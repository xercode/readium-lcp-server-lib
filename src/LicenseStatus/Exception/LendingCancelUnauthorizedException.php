<?php

namespace Xercode\Readium\LicenseStatus\Exception;

use Xercode\Readium\Exception\ForbiddenRequestException;
use Xercode\Readium\Model\Device;

class LendingCancelUnauthorizedException extends ForbiddenRequestException
{
    /**
     * @var string
     */
    protected $licenseId;


    public function __construct(string $licenseId, string $message = 'Rejected request!', \Exception $previous = null)
    {
        if ($message == null || empty($message)) {
            $message = sprintf('Unauthorized cancel your publication %s.', $licenseId);
        }

        $this->licenseId = $licenseId;

        parent::__construct($message, $previous);
    }

    /**
     * @return string
     */
    public function licenseId(): string
    {
        return $this->licenseId;
    }
}
