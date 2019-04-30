<?php

namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

class Encryption
{
    /**
     * @var UserKey
     *
     * @Serializer\Type("Xercode\Readium\Model\UserKey")
     * @Serializer\SerializedName("user_key")
     */
    protected $userKey;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("profile")
     */
    protected $profile;

    /**
     * @var ContentKey|null
     *
     * @Serializer\Type("Xercode\Readium\Model\ContentKey")
     * @Serializer\SerializedName("content_key")
     */
    protected $contentKey;

    /**
     * Encryption constructor.
     *
     * @param UserKey         $userKey
     * @param string|null     $profile
     * @param ContentKey|null $contentKey
     */
    public function __construct(UserKey $userKey, ?string $profile = null, ?ContentKey $contentKey = null)
    {
        $this->userKey    = $userKey;
        $this->profile    = $profile;
        $this->contentKey = $contentKey;
    }


    /**
     * @return UserKey
     */
    public function getUserKey(): UserKey
    {
        return $this->userKey;
    }

    /**
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @return ContentKey|null
     */
    public function getContentKey(): ?ContentKey
    {
        return $this->contentKey;
    }

    /**
     * @param string|null $profile
     */
    public function setProfile(?string $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @param ContentKey|null $contentKey
     */
    public function setContentKey(?ContentKey $contentKey): void
    {
        $this->contentKey = $contentKey;
    }
}
