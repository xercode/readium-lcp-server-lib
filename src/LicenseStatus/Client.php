<?php


namespace Xercode\Readium\LicenseStatus;

use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerAwareTrait;
use Xercode\Readium\Component\HttpStatus;
use Xercode\Readium\Exception\UnauthorizedException;
use Xercode\Readium\Exception\BadRequestException;
use Xercode\Readium\Exception\LicenseNotFoundException;
use Xercode\Readium\LicenseStatus\Exception\DeviceRegistrationBadRequestException;
use Xercode\Readium\LicenseStatus\Exception\LendingCancelBadRequestException;
use Xercode\Readium\LicenseStatus\Exception\LendingCancelUnauthorizedException;
use Xercode\Readium\LicenseStatus\Exception\LendingRenewalBadRequestException;
use Xercode\Readium\LicenseStatus\Exception\LendingRenewalForbiddenException;
use Xercode\Readium\LicenseStatus\Exception\LendingReturnBadRequestException;
use Xercode\Readium\LicenseStatus\Exception\LendingReturnForbiddenException;
use Xercode\Readium\LicenseStatus\Exception\LendingRevokeBadRequestException;
use Xercode\Readium\LicenseStatus\Exception\LendingRevokeUnauthorizedException;
use Xercode\Readium\LicenseStatus\Exception\LicenseStatusServerException;
use Xercode\Readium\Model\Device;
use Xercode\Readium\Model\License;
use Xercode\Readium\Model\LicenseStatus;
use Xercode\Readium\Model\RegisteredDevices;

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
     * Notify of a new license
     *  This method is called by the License Server after generation of a license.
     * The complete license is provided as a payload; the Status Server then extracts the information it needs from the
     * license structure.
     *
     * @see(https://readium.org/technical/readium-lcp-specification/#3-license-document)
     *
     * @param License $license The License Document
     *
     * @return bool true fi license status is created false other case.
     */
    protected function notifyNewLicense(License $license)
    {
        try {
            $jsonRequest = $this->serializer->serialize($license, 'json');
            $response    = $this->httpClient->put('/licenses', ['body' => $jsonRequest]);

            return $response->getStatusCode() === HttpStatus::CREATED;

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::UNAUTHORIZED) {
                throw new UnauthorizedException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::BAD_REQUEST) {
                throw new BadRequestException($exception->getMessage(), $exception);
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }

    }

    /**
     * Return a license status document
     *
     * The method generates and returns a license status document, using a license identifier as key.
     *
     * @param string $licenseId Unique identifier for the License
     *
     * @return LicenseStatus The Status Document
     * @see https://readium.org/technical/readium-lsd-specification#21-content-conformance
     */
    public function licenseStatus(string $licenseId)
    {
        try {

            $response = $this->httpClient->get('/licenses/'.$licenseId.'/status');

            $jsonResponse  = $response->getBody()->getContents();
            $licenseStatus = $this->serializer->deserialize($jsonResponse, LicenseStatus::class, 'json');

            return $licenseStatus;

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception->getCode() == HttpStatus::UNAUTHORIZED) {
                throw new UnauthorizedException($exception->getMessage(), $exception);
            }

            if ($exception->getCode() == HttpStatus::NOT_FOUND) {
                throw new LicenseNotFoundException($exception->getMessage(), $exception);
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Process a device registration
     *
     * Activate a device for a given license.
     *
     * @param string $licenseId Unique identifier for the License
     * @param Device $device    The user device
     *
     * @return LicenseStatus The Status Document
     *
     * @see https://readium.org/technical/readium-lsd-specification#33-registering-a-device
     */
    public function licenseRegisterDevice(string $licenseId, Device $device)
    {
        try {

            $queryString = '?id='.$device->getDeviceId().'&name='.$device->getDeviceNameURLEncoded();
            $response    = $this->httpClient->post('/licenses/'.$licenseId.'/register'.$queryString);

            $jsonResponse  = $response->getBody()->getContents();
            $licenseStatus = $this->serializer->deserialize($jsonResponse, LicenseStatus::class, 'json');

            return $licenseStatus;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new DeviceRegistrationBadRequestException(
                        $licenseId,
                        $device,
                        $exception->getMessage(),
                        $exception
                    );
                }

                if ($code === HttpStatus::NOT_FOUND) {
                    throw new LicenseNotFoundException($licenseId);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);

        }
    }

    /**
     * Process a lending return
     *
     * The method checks that the calling device is activated,
     * then modifies the end date associated with the given license; it will be set to “now”.
     * This method calls the “rights” method of the LCP server.
     *
     * @param string $licenseId Unique identifier for the License
     * @param Device $device    The user device
     *
     * @return LicenseStatus The Status Document
     *
     * @see https://readium.org/technical/readium-lsd-specification#35-renewing-a-license
     */
    public function lendingReturn(string $licenseId, Device $device)
    {
        try {

            $queryString = '?id='.$device->getDeviceId().'&name='.$device->getDeviceNameURLEncoded();
            $response    = $this->httpClient->put('/licenses/'.$licenseId.'/return'.$queryString);

            $jsonResponse  = $response->getBody()->getContents();
            $licenseStatus = $this->serializer->deserialize($jsonResponse, LicenseStatus::class, 'json');

            return $licenseStatus;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new LendingReturnBadRequestException(
                        $licenseId, $device, $exception->getMessage(), $exception
                    );
                }

                if ($code === HttpStatus::FORBIDDEN) {
                    throw new LendingReturnForbiddenException(
                        $licenseId, $device, $exception->getMessage(), $exception
                    );
                }

                if ($code === HttpStatus::NOT_FOUND) {
                    throw new LicenseNotFoundException($licenseId);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Process a lending renewal
     *
     * @param string   $licenseId  Unique identifier for the License
     * @param Device   $device     The user device
     * @param DateTime $newEndDate |null if set null put default date
     *
     * @return LicenseStatus The Status Document
     *
     * @see https://readium.org/technical/readium-lsd-specification#35-renewing-a-license
     *
     */
    public function lendingRenewal(string $licenseId, ?Device $device = null, ?DateTime $newEndDate = null)
    {
        try {

            $queryParameters = [];
            $queryString     = '';

            if ($device !== null) {
                $queryParameters['id']   = $device->getDeviceId();
                $queryParameters['name'] = $device->getDeviceNameURLEncoded();
            }

            if ($newEndDate !== null) {
                $queryParameters['end'] = $newEndDate->format(DateTime::W3C);
            }

            if (!empty($queryParameters)) {
                $queryString = '?'.http_build_query($queryParameters);
            }

            $url = '/licenses/'.$licenseId.'/renew'.$queryString;

            $response = $this->httpClient->put($url);


            $jsonResponse  = $response->getBody()->getContents();
            $licenseStatus = $this->serializer->deserialize($jsonResponse, LicenseStatus::class, 'json');

            return $licenseStatus;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new LendingRenewalBadRequestException(
                        $licenseId,
                        $device,
                        $exception->getMessage(),
                        $exception
                    );
                }

                if ($code === HttpStatus::FORBIDDEN) {
                    throw new LendingRenewalForbiddenException(
                        $licenseId, $device, $exception->getMessage(), $exception
                    );
                }

                if ($code === HttpStatus::NOT_FOUND) {
                    throw new LicenseNotFoundException($licenseId);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Cancel a license
     *
     * This methods cancels (before use) a license by sending a partial license status document.
     *
     * @param string $licenseId Unique identifier for the License
     * @param string $message|null   the message reason for cancel a license
     *
     * @return boolean true if cancelled is OK false other case.
     */
    public function lendingCancel(string $licenseId, ?string $message = 'The license is cancel by system.')
    {
        try {

            if ($message == null || empty($message)) {
                $message = sprintf('The licenseId %s is cancel by system.', $licenseId);
            }

            $jsonRequest = json_encode(
                [
                    'status' => LicenseStatus::cancelled,
                    'message' => $message,
                ]
            );

            $response = $this->httpClient->patch('/licenses/'.$licenseId.'/status', ['body' => $jsonRequest]);

            return $response->getStatusCode() === HttpStatus::OK;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new LendingCancelBadRequestException($licenseId, $exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::FORBIDDEN) {
                    throw new LendingCancelUnauthorizedException($licenseId, $exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::NOT_FOUND) {
                    throw new LicenseNotFoundException($licenseId);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * Revoke a license
     *
     * This methods revokes after use a license by sending a partial license status document
     *
     * @param string $licenseId Unique identifier for the License
     * @param string $message|null   the message reason for revoked a license
     *
     * @return boolean true if revoked is OK false other case.
     */
    public function lendingRevoke(string $licenseId, ?string $message = 'The license is revoked by system.')
    {
        try {

            if ($message == null || empty($message)) {
                $message = sprintf('The licenseId %s is revoke by system.', $licenseId);
            }

            $jsonRequest = json_encode(
                [
                    'status' => LicenseStatus::revoked,
                    'message' => $message,
                ]
            );

            $response = $this->httpClient->patch('/licenses/'.$licenseId.'/status', ['body' => $jsonRequest]);

            return $response->getStatusCode() === HttpStatus::OK;

        } catch (\Exception $exception) {

            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new LendingRevokeBadRequestException($licenseId, $exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::FORBIDDEN) {
                    throw new LendingRevokeUnauthorizedException($licenseId, $exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::NOT_FOUND) {
                    throw new LicenseNotFoundException($licenseId);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * List licenses statuses with a filter
     *
     * This method returns a sequence of license statuses, in their id order.
     *
     * It is used to filter the licenses that have been used by a large number of devices.
     * The devices parameter represents the minimum number of devices.
     * The pagination mechanism is based on the Link headers RFC @link https://tools.ietf.org/html/rfc5988
     * and the Github pagination syntax @link https://developer.github.com/v3/#pagination.
     *
     * @param int|null $numberOfDevices minimum number of devices default is 1
     * @param int|null $page            number of page.
     * @param int|null $perPage         number of element by page
     *
     * @return LicenseStatus[]|null Lis to document statuses
     */
    public function licenses(?int $numberOfDevices = null, ?int $page = null, ?int $perPage = null)
    {
        if ($numberOfDevices != null && $numberOfDevices < 1) {
            throw new \InvalidArgumentException('The parameter "numberOfDevices" must be positive number.');
        }

        if ($page != null && $page < 1) {
            throw new \InvalidArgumentException('The parameter "page" must be positive number.');
        }

        if ($perPage != null && $perPage < 1) {
            throw new \InvalidArgumentException('The parameter "perPage" must be positive number.');
        }

        try {
            $queryParameters = [];
            $queryString     = '';

            if ($numberOfDevices != null) {
                $queryParameters['devices'] = $numberOfDevices;
            }

            if ($page != null) {
                $queryParameters['page'] = $page;
            }

            if ($perPage != null) {
                $queryParameters['per_page'] = $perPage;
            }

            if (!empty($queryParameters)) {
                $queryString = http_build_query($queryString);
            }

            $response = $this->httpClient->get('/licenses?'.$queryString);

            $jsonResponse = $response->getBody()->getContents();
            $licenses     = $this->serializer->deserialize(
                $jsonResponse,
                sprintf('array<%s>', LicenseStatus::class),
                'json'
            );

            return $licenses;
        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::UNAUTHORIZED) {
                    throw new UnauthorizedException($exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new BadRequestException($exception->getMessage(), $exception);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }

    /**
     * List all registered devices for a given license
     *
     * @param string $licenseId Unique identifier for the License
     *
     * @return RegisteredDevices|null The registered devices
     */
    public function listRegisteredDevices(string $licenseId)
    {
        try {

            $response = $this->httpClient->get('/licenses/'.$licenseId.'/registered');

            $jsonResponse      = $response->getBody()->getContents();
            $registeredDevices = $this->serializer->deserialize($jsonResponse, RegisteredDevices::class, 'json');

            return $registeredDevices;

        } catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());

            if ($exception instanceof RequestException) {
                $code = $exception->getCode();

                if ($code === HttpStatus::UNAUTHORIZED) {
                    throw new UnauthorizedException($exception->getMessage(), $exception);
                }

                if ($code === HttpStatus::BAD_REQUEST) {
                    throw new BadRequestException($exception->getMessage(), $exception);
                }
            }

            throw new LicenseStatusServerException($exception->getMessage(), $exception);
        }
    }
}
