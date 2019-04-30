<?php


namespace Xercode\Readium\Model;

use JMS\Serializer\Annotation as Serializer;
use Xercode\Readium\Exception\InvalidArgumentException;
use Xercode\Readium\Exception\NotFoundException;

class ProtectedContent
{
    CONST HASH_ALGORITHM = 'sha256';

    /**
     * Content identifier
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("content-id")
     */
    protected $id;

    /**
     * Content encryption key
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("content-encryption-key")
     */
    protected $encryptionKey;

    /**
     * complete file path of the encrypted content
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("protected-content-location")
     */
    protected $location;


    /**
     * file name of the encrypted content
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("protected-content-disposition")
     */
    protected $fileName;

    /**
     * Size of the encrypted content
     *
     * @var integer
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("protected-content-length")
     */
    protected $length;


    /**
     * Hash of the encrypted content
     *
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("protected-content-sha256")
     */
    protected $hash;

    /**
     * LCPEncryptReturn constructor.
     *
     * @param string $id
     * @param string $encryptionKey
     * @param string $location
     * @param string $fileName
     * @param int    $length
     * @param string $hash
     */
    public function __construct(
        string $id,
        string $encryptionKey,
        string $location,
        string $fileName,
        int $length,
        string $hash
    ) {

        if (empty($id)) {
            throw new InvalidArgumentException('The content identifier cannot be empty.');
        }

        if (empty($encryptionKey)) {
            throw new InvalidArgumentException('The content encryption key cannot be empty.');
        }


        if (empty($fileName)) {
            throw new InvalidArgumentException('The file name of the encrypted content cannot be empty.');
        }

        if (empty($location) || !file_exists($location)) {
            throw new NotFoundException(sprintf('The resource %s not found or is not readable.', $location));
        }


        if (filesize($location) !== $length) {
            throw new InvalidArgumentException(sprintf('The size of the encrypted content %s not match.', $id));
        }

        if (hash_file(self::HASH_ALGORITHM, $location) !== $hash) {
            throw new InvalidArgumentException(sprintf('The size of the encrypted content %s not match.', $id));
        }

        $this->id            = $id;
        $this->encryptionKey = $encryptionKey;
        $this->location      = $location;
        $this->fileName      = $fileName;
        $this->length        = $length;
        $this->hash          = $hash;
    }

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
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
