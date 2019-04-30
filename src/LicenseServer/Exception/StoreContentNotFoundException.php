<?php

namespace Xercode\Readium\LicenseServer\Exception;

use Xercode\Readium\Model\ProtectedContent;

class StoreContentNotFoundException extends ContentNotFoundException
{
    /**
     * @var ProtectedContent
     */
    protected $content;

    public function __construct(ProtectedContent $content, string $message = 'Bad request!', \Exception $previous = null)
    {
        parent::__construct($content->getId(), $message, $previous);

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
