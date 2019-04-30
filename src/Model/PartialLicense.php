<?php

namespace Xercode\Readium\Model;

use DateTime;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class PartialLicense
 *
 * @package Xercode\Readium\Model
 */
class PartialLicense
{
    /**
     * A URI that identifies the provider in an unambiguous way
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("provider")
     */
    protected $provider;


    /**
     * The user information which must appear in the license
     *
     * @var User
     *
     * @Serializer\Type("Xercode\Readium\Model\User")
     * @Serializer\SerializedName("user")
     */
    protected $user;

    /**
     * The encryption profile
     *
     * @var Encryption
     * @Serializer\Type("Xercode\Readium\Model\Encryption")
     * @Serializer\SerializedName("encryption")
     */
    protected $encryption;

    /**
     * @var Rights
     * @Serializer\Type("Xercode\Readium\Model\Rights")
     * @Serializer\SerializedName("rights")
     *
     */
    protected $rights;

    /**
     * The license identifier
     *
     * @var string |null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * PartialLicense constructor.
     *
     * @param string     $provider
     * @param User       $user
     * @param Encryption $encryption
     * @param Rights     $rights
     */
    public function __construct(
        string $provider,
        User $user,
        Encryption $encryption,
        Rights $rights
    ) {
        $this->provider   = $provider;
        $this->user       = $user;
        $this->encryption = $encryption;
        $this->rights     = $rights;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Encryption
     */
    public function getEncryption(): Encryption
    {
        return $this->encryption;
    }

    /**
     * @return Rights
     */
    public function getRights(): Rights
    {
        return $this->rights;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
