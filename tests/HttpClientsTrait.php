<?php


namespace Xercode\Readium;


use GuzzleHttp\Client as GuzzleHttpClient;
use Xercode\Readium\Component\Serializer\Handler\ParameterBagHandler;
use Xercode\Readium\LicenseServer\Client as LicenseServerHttpClient;
use Xercode\Readium\LicenseStatus\Client as LicenseStatusHttpClient;
use Xercode\Readium\Model\Encryption;
use Xercode\Readium\Model\PartialLicense;
use Xercode\Readium\Model\Rights;
use Xercode\Readium\Model\User;
use Xercode\Readium\Model\UserKey;

trait HttpClientsTrait
{
    /**
     * Build Serializer
     *
     * @return \JMS\Serializer\Serializer
     */
    private function buildSerializer()
    {
        return \JMS\Serializer\SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(
                function (\JMS\Serializer\Handler\HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new ParameterBagHandler());
                }
            )
            ->build();
    }

    /**
     * Create Http Client with Guzzle
     *
     * @param string $baseUri
     * @param string $token
     * @return GuzzleHttpClient
     */
    private function buildGuzzleHttpClient($baseUri, $token)
    {
        return new GuzzleHttpClient(
            [
                'base_uri' => $baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json',
                    'Authorization' => sprintf('Basic %s', $token),
                ],
            ]
        );
    }

    /**
     * Build LicenseServer
     *
     * @return LicenseServerHttpClient
     */
    public function buildLicenseServerHttpClient()
    {
        $baseUri          = 'https://lcp-dev.xebook.es';
        $token            = base64_encode('admin:admin');
        $guzzleHttpClient = $this->buildGuzzleHttpClient($baseUri, $token);
        $serializer       = $this->buildSerializer();

        return new LicenseServerHttpClient($guzzleHttpClient, $serializer);
    }

    /**
     * Build LicenseServer
     *
     * @return LicenseServerHttpClient
     */
    public function buildLicenseStatusHttpClient()
    {
        $baseUri          = 'https://lsd-dev.xebook.es';
        $token            = base64_encode('admin:admin');
        $guzzleHttpClient = $this->buildGuzzleHttpClient($baseUri, $token);
        $serializer       = $this->buildSerializer();

        return new LicenseStatusHttpClient($guzzleHttpClient, $serializer);
    }
}
