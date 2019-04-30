<?php


namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;

final class LicenseStatus
{
    /**
     * The License Document is available, but the user hasn't accessed the License and/or Status Document yet.
     */
    const ready = 'ready';

    /**
     * The license is active, and a device has been successfully registered for this license.
     * This is the default value if the License Document does not contain a registration link, or a registration
     * mechanism through the license itself.
     */
    const active = 'active';

    /**
     * The license is no longer active, it has been invalidated by the Issuer.
     */
    const revoked = 'revoked';

    /**
     * The license is no longer active, it has been invalidated by the User.
     */
    const returned = 'returned';

    /**
     * The license is no longer active because it was cancelled prior to activation.
     */
    const cancelled = 'cancelled';

    /**
     * The license is no longer active because it has expired.
     */
    const expired = 'expired';

    /**
     * Unique identifier for the License Document associated to the Status Document.
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * Current status of the License.
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("status")
     */
    protected $status;

    /**
     * A message meant to be displayed to the User regarding the current status of the license.
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("message")
     */
    protected $message;

    /**
     * @var Timestamps
     *
     * @Serializer\Type("Xercode\Readium\Model\Timestamps")
     * @Serializer\SerializedName("updated")
     */
    protected $updated;

    /**
     * @var Link[]|null
     *
     * @Serializer\Type("array<Xercode\Readium\Model\Link>")
     * @Serializer\SerializedName("links")
     */
    protected $links;

    /**
     * @var Rights
     *
     * @Serializer\Type("Xercode\Readium\Model\Rights")
     * @Serializer\SerializedName("potential_rights")
     */
    protected $potentialRights;

    /**
     * @var Event[]|null
     *
     * @Serializer\Type("array<Xercode\Readium\Model\Event>")
     * @Serializer\SerializedName("events")
     */
    protected $events;

    /**
     * @var integer|null
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("device_count")
     */
    protected $deviceCount;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return Timestamps
     */
    public function getUpdated(): Timestamps
    {
        return $this->updated;
    }

    /**
     * @return Link[]|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @return Rights
     */
    public function getPotentialRights(): Rights
    {
        return $this->potentialRights;
    }

    /**
     * @return Event[]|null
     */
    public function getEvents(): ?array
    {
        return $this->events;
    }

    /**
     * @return int|null
     */
    public function getDeviceCount(): ?int
    {
        return $this->deviceCount;
    }
}
