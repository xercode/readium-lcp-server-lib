<?php

namespace Xercode\Readium\LicenseServer;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\BadResponseException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerAwareTrait;
use Xercode\Readium\Component\HttpStatus;
use Xercode\Readium\Exception\UnauthorizedException;
use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Exception\LicenseNotFoundException;
use Xercode\Readium\LicenseServer\Exception\ContentNotFoundException;
use Xercode\Readium\LicenseServer\Exception\GenerateLicenseNotFoundException;
use Xercode\Readium\LicenseServer\Exception\LicenseServerException;
use Xercode\Readium\Exception\NotFoundException;
use Xercode\Readium\LicenseServer\Exception\ContentBadRequestException;
use Xercode\Readium\LicenseServer\Exception\StoreContentNotFoundException;
use Xercode\Readium\Model\Encryption;
use Xercode\Readium\Model\ProtectedContent;
use Xercode\Readium\Model\License;
use Xercode\Readium\Model\PartialLicense;
use Xercode\Readium\Model\Rights;
use Xercode\Readium\Model\User;

class Client
{
    use LoggerAwareTrait;

    /**
     * @var GuzzleHttpClient
     */
    private $httpClient;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Client constructor.
     *
     * @param GuzzleHttpClient    $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct(GuzzleHttpClient $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    /**
     * Store the data resulting from an external encryption
     * This method stores data generated by e.g. lcpencrypt. See the corresponding lcpencrypt spec.
     *
     * @param ProtectedContent $protectedContent Content protected by Encryption tool @link https://github.com/readium/readium-lcp-server/wiki/Encryption-tool
     *
     * @return bool true if data is put in server
     *
     * @throws ContentBadRequestException       File not saved
     * @throws UnauthorizedException            The client does not have session
     * @throws StoreContentNotFoundException    File not found
     * @throws LicenseServerException           Internal server error (File was copied, but database was not updated)
     */
    public function addEncryptionData(ProtectedContent $protectedContent)
    {

        try {

            $jsonRequest = $this->serializer->serialize($protectedContent, 'json');
            $response    = $this->httpClient->put(
                '/contents/'.$protectedContent->getId(),
                ['body' => $jsonRequest]
            );

            return $response->getStatusCode() == HttpStatus::CREATED || $response->getStatusCode() == HttpStatus::OK;

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new ContentBadRequestException($protectedContent, $exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::UNAUTHORIZED) {
                throw new UnauthorizedException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new StoreContentNotFoundException($protectedContent, $exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Fetch an encrypted publication
     * This method fetches the encrypted publication stored by the License Server.
     *
     * @param string $contentId content identifier
     * @param string $saveAs    You can save the contents of an object to a file by setting the SaveAs parameter.
     *
     * @return bool true if content exits in server false other case
     *
     * @throws BadRequestException      File not saved
     * @throws ContentNotFoundException        File not found
     * @throws LicenseServerException   Internal server error (File was copied, but database was not updated)
     */
    public function content(string $contentId, string $saveAs)
    {
        try {

            $response = $this->httpClient->get('/contents/'.$contentId, ['sink' => $saveAs]);

            return $response->getStatusCode() === HttpStatus::OK;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new ContentNotFoundException($contentId, $exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }

    }

    /**
     * This method generates a license from the data resulting from a user transaction on the
     * provider frontend.
     *
     * @param string         $contentId      Content identifier
     * @param PartialLicense $partialLicense A partial LCP license document, which will be completed by the License
     *                                       Server.
     * @return License|null License Document
     *
     * @throws GenerateLicenseNotFoundException Content not found
     * @throws LicenseServerException           Internal server error (File was copied, but database was not updated)
     *
     *
     * @see(https://readium.org/technical/readium-lcp-specification#3-license-document)
     */
    public function generateLicense(string $contentId, PartialLicense $partialLicense)
    {
        try {

            $jsonRequest = $this->serializer->serialize($partialLicense, 'json');
            $response    = $this->httpClient->post('/contents/'.$contentId.'/license', ['body' => $jsonRequest]);

            $jsonResponse = $response->getBody()->getContents();

            $license = $this->serializer->deserialize($jsonResponse, License::class, 'json');


            return $license;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new GenerateLicenseNotFoundException($contentId, $partialLicense, $exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * This method generates a license from the data resulting from a user transaction on the provider frontend,
     * and embeds the license in the encrypted publication.
     *
     * The resulting stream is returned to the frontend.
     * The frontend may return the stream directly to the user as a direct download, or save it as a file in a
     * Web accessible location and present to the user a "download" button.
     *
     * @param string         $contentId      Content identifier
     * @param PartialLicense $partialLicense A partial LCP license document, which will be completed by the License
     *                                       Server.
     * @param string         $saveAs         You can save the contents of an object to a file by setting the SaveAs
     *                                       parameter.
     * @return bool true if the licensed publication: Created. false other case
     *
     * @throws BadRequestException Bad request
     * @throws GenerateLicenseNotFoundException Content not found
     * @throws LicenseServerException           Internal server error (File was copied, but database was not updated)
     */
    public function generateLicensedPublication(string $contentId, PartialLicense $partialLicense, string $saveAs)
    {
        try {

            $jsonRequest = $this->serializer->serialize($partialLicense, 'json');
            $response    = $this->httpClient->post(
                '/contents/'.$contentId.'/publication',
                ['body' => $jsonRequest, 'sink' => $saveAs]
            );

            return $response->getStatusCode() === HttpStatus::CREATED;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new GenerateLicenseNotFoundException($contentId, $partialLicense, $exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Fetch an existing license
     *
     * This method returns a license, using its identifier as key.
     *
     * @param string     $licenseId  Unique identifier for the License
     * @param User       $user       The user information
     * @param Encryption $encryption A list of which user object values are encrypted in this License Document
     *
     * @return License|null License Document
     *
     * @throws LicenseNotFoundException License not found
     * @throws LicenseServerException   Internal server error
     *
     * @see(https://readium.org/technical/readium-lcp-specification/#3-license-document)
     */
    public function fetchLicense(string $licenseId, User $user, Encryption $encryption)
    {
        try {

            $userJson       = $this->serializer->serialize($user, 'json');
            $encryptionJson = $this->serializer->serialize($encryption, 'json');

            $jsonRequest = '{ "user":'.$userJson.', "encryption":'.$encryptionJson.'}';

            $response = $this->httpClient->post('/licenses/'.$licenseId, ['body' => $jsonRequest]);

            $jsonResponse = $response->getBody()->getContents();

            $license = $this->serializer->deserialize($jsonResponse, License::class, 'json');


            return $license;

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new LicenseNotFoundException($exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Fetch an existing licensed publication
     *
     * This method returns a licensed publication, using its license identifier as key.
     *
     * The resulting stream is returned to the frontend.
     * The frontend may return the stream directly to the user as a direct download,
     * or save it as a file in a Web accessible location and present to the user a "download" button.
     *
     * @param string     $licenseId  Unique identifier for the License
     * @param User       $user       The user information
     * @param Encryption $encryption A list of which user object values are encrypted in this License Document
     *
     * @return bool true if file with license is download
     *
     * @throws LicenseNotFoundException License not found
     * @throws LicenseServerException   Internal server error
     */
    public function fetchPublication(string $licenseId, User $user, Encryption $encryption, string $saveAs)
    {
        try {

            $userJson       = $this->serializer->serialize($user, 'json');
            $encryptionJson = $this->serializer->serialize($encryption, 'json');

            $jsonRequest = '{ "user":'.$userJson.', "encryption":'.$encryptionJson.'}';

            $response = $this->httpClient->post(
                '/licenses/'.$licenseId.'/publication',
                ['body' => $jsonRequest, 'sink' => $saveAs]
            );


            return $response->getStatusCode() === HttpStatus::CREATED;


        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new LicenseNotFoundException($exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * List licenses
     *
     * This method returns a sequence of partial licenses, in ante-chronological order (on date of issue).
     *
     * The pagination mechanism is based on the Link headers RFC (https://tools.ietf.org/html/rfc5988 )
     *
     * Note that page numbering is 1-based and that omitting the ?page parameter will return the first page.
     * Requests that return multiple items will be paginated to 30 items by default.
     *
     * @param int|null $page    Number of page
     * @param int|null $perPage Number of elements by page
     *
     * @return PartialLicense[]|null List of partial license Document
     *
     * @throws BadRequestException      Wrong pagination parameters (@link http://readium.org/licensed-content-protection/error/badparam)
     * @throws LicenseServerException   Internal server error
     *
     * @see https://readium.org/technical/readium-lcp-specification#3-license-document
     */
    public function listLicenses(?int $page = null, ?int $perPage = null)
    {
        try {

            $queryString = http_build_query(['page' => $page, 'per_page' => $perPage]);
            $response    = $this->httpClient->get('/licenses?'.$queryString);


            $jsonResponse = $response->getBody()->getContents();

            return $this->serializer->deserialize($jsonResponse, sprintf('array<%s>', PartialLicense::class), 'json');

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Get the licenses associated with given content
     *
     * This method returns a sequence of partial licenses, in ante-chronological order (on date of issue).
     *
     * The pagination mechanism is based on the Link headers RFC (https://tools.ietf.org/html/rfc5988 )
     *
     * Note that page numbering is 1-based and that omitting the ?page parameter will return the first page.
     * Requests that return multiple items will be paginated to 30 items by default.
     *
     * @param string   $contentId Content identifier
     * @param int|null $page      Number of page
     * @param int|null $perPage   Number of elements by page
     *
     * @return License[]|null List of License Document
     *
     * @throws BadRequestException       Wrong pagination parameters (@link http://readium.org/licensed-content-protection/error/badparam)
     * @throws ContentNotFoundException  Content not found (@link http://readium.org/licensed-content-protection/error/notfound)
     * @throws LicenseServerException   Internal server error
     *
     * @see https://readium.org/technical/readium-lcp-specification#3-license-document
     */
    public function listLicensesByContent(string $contentId, ?int $page = null, ?int $perPage = null)
    {
        try {

            $queryString = http_build_query(['page' => $page, 'per_page' => $perPage]);
            $response    = $this->httpClient->get('/contents/'.$contentId.'/licenses?'.$queryString);

            $jsonResponse = $response->getBody()->getContents();

            return $this->serializer->deserialize($jsonResponse, sprintf('array<%s>', License::class), 'json');

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new ContentNotFoundException($contentId, $exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Update a license
     *
     * This method can force the update of the information stored in the License Server database, I.e.
     *
     * @param License $license A License Document

     * @return bool true if license is update, false other case
     *
     * @throws BadRequestException       The partial license is malformed (@link http://readium.org/licensed-content-protection/error/malformed)
     * @throws LicenseNotFoundException  The license does not exist in the database (@link http://readium.org/licensed-content-protection/error/notfound)
     *
     * @see https://readium.org/technical/readium-lcp-specification#3-license-document
     */
    public function updateLicense(License $license)
    {
        try {

            $jsonRequest = $this->serializer->serialize($license, 'json');
            $response    = $this->httpClient->patch('/licenses/'.$license->getId(), ['body' => $jsonRequest]);


            return $response->getStatusCode() === HttpStatus::OK;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new LicenseNotFoundException($exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Update a license Rights
     *
     * This method can force the update the Rights for one license
     *
     * @param string $licenseId Unique identifier for the License
     * @param Rights $rights    The set of rights associated with the license
     *
     * @return bool true if license is update, false other case
     *
     * @throws BadRequestException       The partial license is malformed (@link http://readium.org/licensed-content-protection/error/malformed)
     * @throws LicenseNotFoundException  The license does not exist in the database (@link http://readium.org/licensed-content-protection/error/notfound)
     *
     * @see https://readium.org/technical/readium-lcp-specification#3-license-document
     */
    public function updateLicenseRights(string $licenseId, Rights $rights)
    {
        try {

            $jsonRights  = $this->serializer->serialize($rights, 'json');
            $jsonRequest = '{"rights": '.$jsonRights.'}';

            $response = $this->httpClient->patch('/licenses/'.$licenseId, ['body' => $jsonRequest]);

            return $response->getStatusCode() === HttpStatus::OK;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new LicenseNotFoundException($exception->getMessage(), $exception);
            }

            throw new LicenseServerException($exception->getMessage(), $exception);
        }
    }
}
