<?php


namespace Xercode\Readium\Fixtures;


use Xercode\Readium\Model\Device;
use Xercode\Readium\Model\Encryption;
use Xercode\Readium\Model\PartialLicense;
use Xercode\Readium\Model\Rights;
use Xercode\Readium\Model\User;
use Xercode\Readium\Model\UserKey;

class LicenseServerFixtures
{
    const CONTENT_ID = '9786071114419';

    /**
     * @var string
     */
    private $storeFilesystemDirectory;

    /**
     * LicenseServerFixtures constructor.
     */
    public function __construct()
    {
        if (!is_dir(getenv('STORAGE_FILESYSTEM_DIRECTORY'))) {
            throw new \RuntimeException('The var STORAGE_FILESYSTEM_DIRECTORY is not configured.');
        }

        $this->storeFilesystemDirectory = getenv('STORAGE_FILESYSTEM_DIRECTORY');

    }


    /**
     * Get Encrypted File
     *
     * @return string
     */
    public function encryptedFileIntoStore()
    {

        return sprintf('%s/%s.lcp.epub', $this->storeFilesystemDirectory, self::CONTENT_ID);
    }

    /**
     * Get Encrypted File as fixture
     *
     * @return string
     */
    public function encryptedFileIntoFixtures()
    {
        return sprintf('%s/%s.lcp.epub', __DIR__, self::CONTENT_ID);
    }

    /**
     * Get Encrypted File with license
     *
     * @return string
     */
    public function encryptedFileWithLicense()
    {
        return sprintf('%s/%s', getenv('STORAGE_FILESYSTEM_DIRECTORY'), self::CONTENT_ID);
    }

    public function setEncryptedFileIntoStore()
    {
        if (file_exists($this->encryptedFileWithLicense())) {
            @unlink($this->encryptedFileWithLicense());
        }

        if (!file_exists($this->encryptedFileWithLicense())) {

            $source      = $this->encryptedFileIntoFixtures();
            $destination = $this->encryptedFileIntoStore();

            copy($source, $destination);
        }
    }

    /***
     * @return User
     */
    public function user()
    {
        return new User(
            'demo@xebook.es', ['email', 'name'], 'd9f298a7-7f34-49e7-8aae-4378ecb1d597', 'xercode', [
                'https://xeread.xebook.es/lcp/user/language' => 'es',
                'https://xeread.xebook.es/lcp/library/name' => 'xeread',
            ]
        );
    }

    /***
     * @return Encryption
     */
    public function userEncryption()
    {
        return new Encryption(
            new UserKey(
                'Enter the username address which authentifies you on xeread.xebook.es',
                '4981AA0A50D563040519E9032B5D74367B1D129E239A1BA82667A57333866494'
            )
        );
    }

    /**
     * @return Rights
     * @throws \Exception
     */
    public function rights()
    {
        return new Rights(10, 1024, new \DateTime('now'), new \DateTime('+1 days'));
    }

    /**
     * @return PartialLicense
     * @throws \Exception
     */
    public function partialLicense()
    {
        $provider   = 'https://xeread.xebook.es';
        $user       = $this->user();
        $encryption = $this->userEncryption();
        $rights     = $this->rights();

        return new PartialLicense($provider, $user, $encryption, $rights);
    }

    /**
     * @return Device
     */
    public function devide()
    {
        return new Device(
            uniqid('test_'.time()),
            'SNE-LX1 27 HUAWEI/SNE-LX1/HWSNE:8.1.0/HUAWEISNE-LX1/130(C40):user/release-keys'
        );
    }
}
