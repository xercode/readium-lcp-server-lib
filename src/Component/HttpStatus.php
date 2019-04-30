<?php

namespace Xercode\Readium\Component;

use InvalidArgumentException;

final class HttpStatus
{
    // 1xx Informational
    /**
     * 100 Continue.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.1.1">HTTP/1.1</a>
     */
    const CONTINUE_OK = 100;
    /**
     * 101 Switching Protocols.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.1.2">HTTP/1.1</a>
     */
    const SWITCHING_PROTOCOLS = 101;
    /**
     * 102 Processing.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2518#section-10.1">WebDAV</a>
     */
    const PROCESSING = 102;

    // 2xx Success

    /**
     * 200 OK.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.1">HTTP/1.1</a>
     */
    const OK = 200;
    /**
     * 201 Created.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.2">HTTP/1.1</a>
     */
    const CREATED = 201;
    /**
     * 202 Accepted.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.3">HTTP/1.1</a>
     */
    const ACCEPTED = 202;
    /**
     * 203 Non-Authoritative Information.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.4">HTTP/1.1</a>
     */
    const NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * 204 No Content.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.5">HTTP/1.1</a>
     */
    const NO_CONTENT = 204;
    /**
     * 205 Reset Content.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.6">HTTP/1.1</a>
     */
    const RESET_CONTENT = 205;
    /**
     * 206 Partial Content.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.2.7">HTTP/1.1</a>
     */
    const PARTIAL_CONTENT = 206;
    /**
     * 207 Multi-Status.
     * @see <a href="http://tools.ietf.org/html/rfc4918#section-13">WebDAV</a>
     */
    const MULTI_STATUS = 207;
    /**
     * 208 Already Reported.
     *
     * @see <a href="http://tools.ietf.org/html/draft-ietf-webdav-bind-27#section-7.1">WebDAV Binding Extensions</a>
     */
    const ALREADY_REPORTED = 208;
    /**
     * 226 IM Used.
     *
     * @see <a href="http://tools.ietf.org/html/rfc3229#section-10.4.1">Delta encoding in HTTP</a>
     */
    const IM_USED = 226;

    // 3xx Redirection

    /**
     * 300 Multiple Choices.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.1">HTTP/1.1</a>
     */
    const MULTIPLE_CHOICES = 300;
    /**
     * 301 Moved Permanently.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.2">HTTP/1.1</a>
     */
    const MOVED_PERMANENTLY = 301;
    /**
     * 302 Found.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.3">HTTP/1.1</a>
     */
    const FOUND = 302;
    /**
     * 302 Moved Temporarily.
     *
     * @see <a href="http://tools.ietf.org/html/rfc1945#section-9.3">HTTP/1.0</a>
     */
    const MOVED_TEMPORARILY = 302;
    /**
     * 303 See Other.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.4">HTTP/1.1</a>
     */
    const SEE_OTHER = 303;
    /**
     * 304 Not Modified.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.5">HTTP/1.1</a>
     */
    const NOT_MODIFIED = 304;
    /**
     * 305 Use Proxy.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.6">HTTP/1.1</a>
     */
    const USE_PROXY = 305;
    /**
     * 307 Temporary Redirect.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.3.8">HTTP/1.1</a>
     */
    const TEMPORARY_REDIRECT = 307;

    // --- 4xx Client Error ---

    /**
     * 400 Bad Request.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.1">HTTP/1.1</a>
     */
    const BAD_REQUEST = 400;
    /**
     * 401 Unauthorized.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.2">HTTP/1.1</a>
     */
    const UNAUTHORIZED = 401;
    /**
     * 402 Payment Required.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.3">HTTP/1.1</a>
     */
    const PAYMENT_REQUIRED = 402;
    /**
     * 403 Forbidden.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.4">HTTP/1.1</a>
     */
    const FORBIDDEN = 403;
    /**
     * 404 Not Found.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.5">HTTP/1.1</a>
     */
    const NOT_FOUND = 404;
    /**
     * 405 Method Not Allowed.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.6">HTTP/1.1</a>
     */
    const METHOD_NOT_ALLOWED = 405;
    /**
     * 406 Not Acceptable.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.7">HTTP/1.1</a>
     */
    const NOT_ACCEPTABLE = 406;
    /**
     * 407 Proxy Authentication Required.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.8">HTTP/1.1</a>
     */
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    /**
     * 408 Request Timeout.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.9">HTTP/1.1</a>
     */
    const REQUEST_TIMEOUT = 408;
    /**
     * 409 Conflict.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.10">HTTP/1.1</a>
     */
    const CONFLICT = 409;
    /**
     * 410 Gone.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.11">HTTP/1.1</a>
     */
    const GONE = 410;
    /**
     * 411 Length Required.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.12">HTTP/1.1</a>
     */
    const LENGTH_REQUIRED = 411;
    /**
     * 412 Precondition failed.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.13">HTTP/1.1</a>
     */
    const PRECONDITION_FAILED = 412;
    /**
     * 413 Request Entity Too Large.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.14">HTTP/1.1</a>
     */
    const REQUEST_ENTITY_TOO_LARGE = 413;
    /**
     * 414 Request-URI Too Long.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.15">HTTP/1.1</a>
     */
    const REQUEST_URI_TOO_LONG = 414;
    /**
     * 415 Unsupported Media Type.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.16">HTTP/1.1</a>
     */
    const UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * 416 Requested Range Not Satisfiable.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.17">HTTP/1.1</a>
     */
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    /**
     * 417 Expectation Failed.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.4.18">HTTP/1.1</a>
     */
    const EXPECTATION_FAILED = 417;
    /**
     * 419 Insufficient Space on Resource.
     *
     * @see <a href="http://tools.ietf.org/html/draft-ietf-webdav-protocol-05#section-10.4">WebDAV Draft</a>
     */
    const INSUFFICIENT_SPACE_ON_RESOURCE = 419;
    /**
     * 420 Method Failure.
     *
     * @see <a href="http://tools.ietf.org/html/draft-ietf-webdav-protocol-05#section-10.5">WebDAV Draft</a>
     */
    const METHOD_FAILURE = 420;
    /**
     * 421 Destination Locked.
     *
     * @see <a href="http://tools.ietf.org/html/draft-ietf-webdav-protocol-05#section-10.6">WebDAV Draft</a>
     */
    const DESTINATION_LOCKED = 421;
    /**
     * 422 Unprocessable Entity.
     *
     * @see <a href="http://tools.ietf.org/html/rfc4918#section-11.2">WebDAV</a>
     */
    const UNPROCESSABLE_ENTITY = 422;
    /**
     * 423 Locked.
     *
     * @see <a href="http://tools.ietf.org/html/rfc4918#section-11.3">WebDAV</a>
     */
    const LOCKED = 423;
    /**
     * 424 Failed Dependency.
     *
     * @see <a href="http://tools.ietf.org/html/rfc4918#section-11.4">WebDAV</a>
     */
    const FAILED_DEPENDENCY = 424;
    /**
     * 426 Upgrade Required.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2817#section-6">Upgrading to TLS Within HTTP/1.1</a>
     */
    const UPGRADE_REQUIRED = 426;

    // --- 5xx Server Error ---

    /**
     * 500 Internal Server Error.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.1">HTTP/1.1</a>
     */
    const INTERNAL_SERVER_ERROR = 500;
    /**
     * 501 Not Implemented.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.2">HTTP/1.1</a>
     */
    const NOT_IMPLEMENTED = 501;
    /**
     * 502 Bad Gateway.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.3">HTTP/1.1</a>
     */
    const BAD_GATEWAY = 502;
    /**
     * 503 Service Unavailable.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.4">HTTP/1.1</a>
     */
    const SERVICE_UNAVAILABLE = 503;
    /**
     * 504 Gateway Timeout.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.5">HTTP/1.1</a>
     */
    const GATEWAY_TIMEOUT = 504;
    /**
     * 505 HTTP Version Not Supported.
     *
     * @see <a href="http://tools.ietf.org/html/rfc2616#section-10.5.6">HTTP/1.1</a>
     */
    const HTTP_VERSION_NOT_SUPPORTED = 505;
    /**
     * 506 Variant Also Negotiates
     *
     * @see <a href="http://tools.ietf.org/html/rfc2295#section-8.1">Transparent Content Negotiation</a>
     */
    const VARIANT_ALSO_NEGOTIATES = 506;
    /**
     * 507 Insufficient Storage
     *
     * @see <a href="http://tools.ietf.org/html/rfc4918#section-11.5">WebDAV</a>
     */
    const INSUFFICIENT_STORAGE = 507;
    /**
     * 508 Loop Detected
     *
     * @see <a href="http://tools.ietf.org/html/draft-ietf-webdav-bind-27#section-7.2">WebDAV Binding Extensions</a>
     */
    const LOOP_DETECTED = 508;
    /**
     * 510 Not Extended
     *
     * @see <a href="http://tools.ietf.org/html/rfc2774#section-7">HTTP Extension Framework</a>
     */
    const NOT_EXTENDED = 510;

    protected function throwExceptionForInvalidValue($value)
    {
        throw new InvalidArgumentException(sprintf('The value %s is invalid HttpStatus. ', $value));
    }
}
