<?php
declare(strict_types=1);

namespace Xercode\Readium\LicenseServer;

use PHPUnit\Framework\TestCase;
use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Fixtures\LicenseServerFixtures;
use Xercode\Readium\HttpClientsTrait;
use Xercode\Readium\LicenseServer\Client as HttpClient;
use Xercode\Readium\Model\Encryption;
use Xercode\Readium\Model\ProtectedContent;
use Xercode\Readium\Model\License;
use Xercode\Readium\Model\PartialLicense;
use Xercode\Readium\Model\Rights;
use Xercode\Readium\Model\User;
use Xercode\Readium\Model\UserKey;

final class ClientTest extends TestCase
{
    use HttpClientsTrait;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var LicenseServerFixtures
     */
    private $fixtures;


    protected function setUp()
    {
        parent::setUp();
        $this->httpClient = $this->buildLicenseServerHttpClient();
        $this->assertNotNull($this->httpClient);
        $this->fixtures = new LicenseServerFixtures();


    }

    /**
     * @return \Xercode\Readium\Model\License|null
     * @throws \Exception
     */
    protected function generateLicense()
    {
        $contentId      = LicenseServerFixtures::CONTENT_ID;
        $partialLicense = $this->fixtures->partialLicense();

        return $this->buildLicenseServerHttpClient()->generateLicense($contentId, $partialLicense);
    }

    /**
     * @test
     */
    public function licenseServerAddEncryptionData()
    {

        $this->fixtures->setEncryptedFileIntoStore();

        $encryptionToolReturn = new ProtectedContent(
            '9786071114419',
            'DXF1LdZS+q2V/WvXcwxL495oVkQjOwV0jgpGQF/t0j4=',
            '/workspace/lcpconfig/lcpfiles/encrypted/9786071114419.lcp.epub',
            '9786071114419.lcp.epub',
            674328,
            '342d39c2d375b55791806aed4bae3f711abff30a0f5aea41e53ffa06fce55ed2'
        );


        $response = $this->httpClient->addEncryptionData($encryptionToolReturn);

        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function licenseServerContent()
    {
        $contentId = LicenseServerFixtures::CONTENT_ID;

        $response = $this->httpClient->content($contentId, '/tmp/encryptionFile.9786071114419.lcp.epub');
        $this->assertTrue($response);
        $this->assertTrue(file_exists('/tmp/encryptionFile.9786071114419.lcp.epub'));
    }

    /**
     * @throws \Exception
     * @test
     */
    public function licenseServerGenerateLicense()
    {
        $contentId      = LicenseServerFixtures::CONTENT_ID;
        $partialLicense = $this->fixtures->partialLicense();

        $license = $this->httpClient->generateLicense($contentId, $partialLicense);
        $this->assertInstanceOf(License::class, $license);
        $this->assertNotNull($license->getProvider());
        $this->assertNotNull($license->getId());
        $this->assertNotNull($license->getIssued());
        $this->assertNotNull($license->getEncryption());
        $this->assertNotNull($license->getEncryption()->getProfile());
        $this->assertNotNull($license->getEncryption()->getContentKey());
        $this->assertNotNull($license->getEncryption()->getContentKey()->getAlgorithm());
        $this->assertNotNull($license->getEncryption()->getContentKey()->getEncryptedValue());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getAlgorithm());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getTextHint());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getKeyCheck());
        $this->assertNotNull($license->getLinks());
        $this->assertCount(3, $license->getLinks());
        $this->assertNotNull($license->getUser());
        $this->assertNotNull($license->getUser()->getId());
        $this->assertNotNull($license->getUser()->getEmail());
        $this->assertNotNull($license->getUser()->getName());
        $this->assertNotNull($license->getUser()->getEncrypted());
        $this->assertNotEquals('demo@xebook.es', $license->getUser()->getEmail());
        $this->assertNotEquals('xercode', $license->getUser()->getName());
        $this->assertNotNull($license->getRights());
        $this->assertNotNull($license->getRights()->getCopy());
        $this->assertNotNull($license->getRights()->getPrint());
        $this->assertNotNull($license->getRights()->getStart());
        $this->assertNotNull($license->getRights()->getEnd());
        $this->assertNotNull($license->getSignature());
        $this->assertNotNull($license->getSignature()->getCertificate());
        $this->assertNotNull($license->getSignature()->getAlgorithm());
        $this->assertNotNull($license->getSignature()->getValue());

    }

    /**
     * @test
     * @throws \Exception
     */
    public function licenseServerGenerateLicensedPublication()
    {
        $contentId      = LicenseServerFixtures::CONTENT_ID;
        $partialLicense = $this->fixtures->partialLicense();

        $response = $this->httpClient->generateLicensedPublication(
            $contentId,
            $partialLicense,
            '/tmp/9786071114419.lcp.epub'
        );

        $this->assertTrue($response);
        $this->assertTrue(file_exists('/tmp/9786071114419.lcp.epub'));
    }

    /**
     * @test
     */
    public function licenseServerFetchLicense()
    {

        $user = new User('demo@xebook.es', ['email', 'name']);

        $encryption = new Encryption(
            new UserKey(
                'Enter the username address which authentifies you on xeread.xebook.es',
                '4981AA0A50D563040519E9032B5D74367B1D129E239A1BA82667A57333866494'
            )
        );
        $license    = $this->generateLicense();
        $licenseId = $license->getId();

        $license = $this->httpClient->fetchLicense($licenseId, $user, $encryption);

        $this->assertNotNull($license);
        $this->assertInstanceOf(License::class, $license);

        $this->assertNotNull($license->getProvider());
        $this->assertNotNull($license->getId());
        $this->assertNotNull($license->getIssued());
        $this->assertNotNull($license->getEncryption());
        $this->assertNotNull($license->getEncryption()->getProfile());
        $this->assertNotNull($license->getEncryption()->getContentKey());
        $this->assertNotNull($license->getEncryption()->getContentKey()->getAlgorithm());
        $this->assertNotNull($license->getEncryption()->getContentKey()->getEncryptedValue());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getAlgorithm());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getTextHint());
        $this->assertNotNull($license->getEncryption()->getUserKey()->getKeyCheck());
        $this->assertNotNull($license->getLinks());
        $this->assertCount(3, $license->getLinks());
        $this->assertNotNull($license->getUser());
        $this->assertNotNull($license->getUser()->getId());
        $this->assertNotNull($license->getUser()->getEmail());
        $this->assertNotNull($license->getUser()->getName());
        $this->assertNotNull($license->getUser()->getEncrypted());
        $this->assertNotEquals('demo@xebook.es', $license->getUser()->getEmail());
        $this->assertNotEquals('xercode', $license->getUser()->getName());
        $this->assertNotNull($license->getRights());
        $this->assertNotNull($license->getRights()->getCopy());
        $this->assertNotNull($license->getRights()->getPrint());
        $this->assertNotNull($license->getRights()->getStart());
        $this->assertNotNull($license->getRights()->getEnd());
        $this->assertNotNull($license->getSignature());
        $this->assertNotNull($license->getSignature()->getCertificate());
        $this->assertNotNull($license->getSignature()->getAlgorithm());
        $this->assertNotNull($license->getSignature()->getValue());
    }

    /**
     * @test
     */
    public function licenseServerFetchPublication()
    {

        $user = new User('demo@xebook.es', ['email', 'name']);

        $encryption = new Encryption(
            new UserKey(
                'Enter the username address which authentifies you on xeread.xebook.es',
                '4981AA0A50D563040519E9032B5D74367B1D129E239A1BA82667A57333866494'
            )
        );

        $license    = $this->generateLicense();
        $licenseId = $license->getId();

        $response = $this->httpClient->fetchPublication(
            $licenseId,
            $user,
            $encryption,
            '/tmp/9786071114419-ii.lcp.epub'
        );
        $this->assertTrue($response);
        $this->assertTrue(file_exists('/tmp/9786071114419-ii.lcp.epub'));
    }

    /**
     * @test
     */
    public function licenseServerListLicenses()
    {
        $licenses = $this->httpClient->listLicenses();
        $this->assertNotEmpty($licenses);

        foreach ($licenses as $license) {
            $this->assertInstanceOf(PartialLicense::class, $license);
            $this->assertNotNull($license->getProvider());
            $this->assertNotNull($license->getId());
        }

        $licenses = $this->httpClient->listLicenses(1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(1, 1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(null, 1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(0);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(100);
        $this->assertEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(0, 0);
        $this->assertEmpty($licenses);

        $licenses = $this->httpClient->listLicenses(null, 0);
        $this->assertEmpty($licenses);

        try {
            $licenses = $this->httpClient->listLicenses(-1);
        } catch (\Exception $exception) {
            if (!$exception instanceof BadRequestException) {
                $this->fail('The exception BadRequestException is not throw');
            }
        }

    }

    /**
     * @test
     */
    public function licenseServerListLicensesByContent()
    {

        $contentId = LicenseServerFixtures::CONTENT_ID;

        $licenses = $this->httpClient->listLicensesByContent($contentId);
        $this->assertNotEmpty($licenses);

        foreach ($licenses as $license) {
            $this->assertInstanceOf(License::class, $license);
            $this->assertNotNull($license->getProvider());
            $this->assertNotNull($license->getId());
        }

        $licenses = $this->httpClient->listLicensesByContent($contentId, 1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, 1, 1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, null, 1);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, 0);
        $this->assertNotEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, 100);
        $this->assertEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, 0, 0);
        $this->assertEmpty($licenses);

        $licenses = $this->httpClient->listLicensesByContent($contentId, null, 0);
        $this->assertEmpty($licenses);

        try {
            $this->httpClient->listLicensesByContent($contentId, -1);
        } catch (\Exception $exception) {
            if (!$exception instanceof BadRequestException) {
                $this->fail('The exception BadRequestException is not throw');
            }
        }

    }

    /**
     * @test
     */
    public function licenseServerUpdateLicense()
    {
        $contentId      = LicenseServerFixtures::CONTENT_ID;
        $partialLicense = $this->fixtures->partialLicense();

        $license = $this->httpClient->generateLicense($contentId, $partialLicense);
        $this->assertNotNull($license);

        $license->getRights()->getEnd()->add(new \DateInterval('P1Y'));
        $response = $this->httpClient->updateLicense($license);
        $this->assertTrue($response);

    }

    /**
     * @test
     */
    public function licenseServerUpdateLicenseRights()
    {
        $license    = $this->generateLicense();
        $licenseId = $license->getId();

        $rights    = new Rights(100, 100, new \DateTime('now'), new \DateTime('+ 2 days'));

        $response = $this->httpClient->updateLicenseRights($licenseId, $rights);
        $this->assertTrue($response);
    }

}
