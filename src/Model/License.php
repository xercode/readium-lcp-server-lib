<?php

namespace Xercode\Readium\Model;

use DateTime;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class License
 *
 * @package Xercode\Readium\Model
 */
class License
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
     * The date of issue of the license
     *
     * @var DateTime |null
     *
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:sP'>")
     * @Serializer\SerializedName("issued")
     */
    protected $issued;

    /**
     * @var Link[]|null
     *
     * @Serializer\Type("array<Xercode\Readium\Model\Link>")
     * @Serializer\SerializedName("links")
     */
    protected $links;

    /**
     * A signature
     *
     * @var Signature|null
     * @Serializer\Type("Xercode\Readium\Model\Signature")
     * @Serializer\SerializedName("signature")
     */
    protected $signature;

    /**
     * PartialLicense constructor.
     *
     * @param string        $provider
     * @param User          $user
     * @param Encryption    $encryption
     * @param Rights        $rights
     * @param string|null   $id
     * @param DateTime|null $issued
     * @param Link[]|null   $links
     * @param ?Signature|null $signature
     */
    public function __construct(
        string $provider,
        User $user,
        Encryption $encryption,
        Rights $rights,
        ?string $id = null,
        ?DateTime $issued = null,
        ?array $links = null,
        ?Signature $signature = null
    ) {
        $this->provider   = $provider;
        $this->user       = $user;
        $this->encryption = $encryption;
        $this->rights     = $rights;
        $this->id         = $id;
        $this->issued     = $issued;
        $this->links      = $links;
        $this->signature  = $signature;
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

    /**
     * @return DateTime|null
     */
    public function getIssued(): ?DateTime
    {
        return $this->issued;
    }

    /**
     * @return Link[]|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @return Signature|null
     */
    public function getSignature(): ?Signature
    {
        return $this->signature;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }
}
