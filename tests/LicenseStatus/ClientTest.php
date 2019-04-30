<?php
declare(strict_types=1);

namespace Xercode\Readium\LicenseStatus;


use PHPUnit\Framework\TestCase;
use Xercode\Readium\Fixtures\LicenseServerFixtures;
use Xercode\Readium\HttpClientsTrait;
use Xercode\Readium\LicenseStatus\Client as HttpClient;
use Xercode\Readium\Model\LicenseStatus;
use Xercode\Readium\Model\RegisteredDevices;

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

        $this->httpClient = $this->buildLicenseStatusHttpClient();
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
    public function licenseStatusLicenseStatus()
    {
        $license    = $this->generateLicense();
        $licenseId = $license->getId();

        $licenseStatus = $this->httpClient->licenseStatus($licenseId);
        $this->assertNotNull($licenseStatus);
        $this->assertInstanceOf(LicenseStatus::class, $licenseStatus);

        $this->assertNotNull($licenseStatus->getId());
        $this->assertNotNull($licenseStatus->getStatus());
        $this->assertNotNull($licenseStatus->getMessage());

        $this->assertNotNull($licenseStatus->getLinks());
        $this->assertNotNull($licenseStatus->getPotentialRights());
    }

    /**
     * @test
     */
    public function licenseStatusLicenseRegisterDevice()
    {
        $license    = $this->generateLicense();
        $licenseId = $license->getId();
        $device    = $this->fixtures->devide();

        $licenseStatus = $this->httpClient->licenseRegisterDevice($licenseId, $device);
        $this->assertNotNull($licenseStatus);
        $this->assertInstanceOf(LicenseStatus::class, $licenseStatus);

        $this->assertNotNull($licenseStatus->getId());
        $status = $licenseStatus->getStatus();
        $this->assertNotNull($status);
        $this->assertEquals(LicenseStatus::active, $status);

        $this->assertNotNull($licenseStatus->getMessage());

        $this->assertNotNull($licenseStatus->getLinks());
        $this->assertNotNull($licenseStatus->getPotentialRights());
        $this->assertNotNull($licenseStatus->getEvents());

    }

    /**
     * @test
     * @throws \Exception
     */
    public function licenseStatusLendingReturn()
    {
        $license   = $this->generateLicense();
        $licenseId = $license->getId();
        $device    = $this->fixtures->devide();

        $this->httpClient->licenseRegisterDevice($licenseId, $device);
        $licenseStatus = $this->httpClient->lendingReturn($licenseId, $device);

        $this->assertNotNull($licenseStatus);
        $this->assertInstanceOf(LicenseStatus::class, $licenseStatus);

        $this->assertNotNull($licenseStatus->getId());
        $status = $licenseStatus->getStatus();
        $this->assertNotNull($status);
        $this->assertEquals(LicenseStatus::returned, $status);

        $this->assertNotNull($licenseStatus->getMessage());

        $this->assertNotNull($licenseStatus->getLinks());
        $this->assertNotNull($licenseStatus->getPotentialRights());
        $events = $licenseStatus->getEvents();
        $this->assertNotNull($events);

    }

    /**
     * @test
     * @throws \Exception
     */
    public function licenseStatusLendingRenewal()
    {
        $license   = $this->generateLicense();
        $licenseId = $license->getId();
        $device    = $this->fixtures->devide();
        $end       = new \DateTime('+2 days');

        $this->httpClient->licenseRegisterDevice($licenseId, $device);
        $licenseStatus = $this->httpClient->lendingRenewal($licenseId, $device, $end);

        $this->assertNotNull($licenseStatus);
        $this->assertInstanceOf(LicenseStatus::class, $licenseStatus);

        $this->assertNotNull($licenseStatus->getId());
        $status = $licenseStatus->getStatus();
        $this->assertNotNull($status);
        $this->assertEquals(LicenseStatus::active, $status);

        $this->assertNotNull($licenseStatus->getMessage());

        $this->assertNotNull($licenseStatus->getLinks());
        $this->assertNotNull($licenseStatus->getPotentialRights());
        $events = $licenseStatus->getEvents();
        $this->assertNotNull($events);

    }

    /**
     * @test
     */
    public function licenseStatusLendingCancel()
    {
        $license   = $this->generateLicense();
        $licenseId = $license->getId();

        $result = $this->httpClient->lendingCancel($licenseId);
        $this->assertTrue($result);

    }

    /**
     * @test
     */
    public function licenseStatusLendingRevoke()
    {
        $license    = $this->generateLicense();
        $licenseId  = $license->getId();
        $device     = $this->fixtures->devide();
        $this->httpClient->licenseRegisterDevice($licenseId, $device);

        $result = $this->httpClient->lendingRevoke($licenseId);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function licenseStatusLicenses()
    {
        $licenses = $this->httpClient->licenses();
        $this->assertNotNull($licenses);
        $this->assertGreaterThanOrEqual(1, $licenses);
        $license = $licenses[0];
        $this->assertInstanceOf(LicenseStatus::class, $license);
        $this->assertNotNull($license->getId());
        $this->assertNotNull($license->getStatus());
        $this->assertNotNull($license->getUpdated());
        $this->assertNotNull($license->getDeviceCount());
        $this->assertGreaterThanOrEqual(1, $license->getDeviceCount());


    }

    /**
     * @test
     */
    public function licenseStatusListRegisteredDevices()
    {
        $license    = $this->generateLicense();
        $device     = $this->fixtures->devide();
        $licenseId  = $license->getId();


        $this->httpClient->licenseRegisterDevice($licenseId, $device);
        $registeredDevices = $this->httpClient->listRegisteredDevices($licenseId);
        $this->assertNotNull($registeredDevices);
        $this->assertInstanceOf(RegisteredDevices::class, $registeredDevices);
        $this->assertEquals($licenseId, $registeredDevices->getLicenseId());
        $this->assertNotNull($registeredDevices->getDevices());
        $this->assertNotEmpty($registeredDevices->getDevices());
    }
}
