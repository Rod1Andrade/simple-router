<?php


namespace Rodri\SimpleRouter\Helpers;

/**
 * Class StatusCode - HTTP/1.0 Status code
 * @package Rodri\SimpleRouter\utils
 * const OK = 200;
 * const CREATED = 201;
 * const ACCEPTED = 202;
 * const NONAUTHORITATIVE_INFORMATION = 203;
 * const NO_CONTENT = 204;
 * const RESET_CONTENT = 205;
 * const PARTIAL_CONTENT = 206;
 * const MULTIPLE_CHOICES = 300;
 * const MOVED_PERMANENTLY = 301;
 * const MOVED_TEMPORARILY = 302;
 * const SEE_OTHER = 303;
 * const NOT_MODIFIED = 304;
 * const USE_PROXY = 305;
 * const BAD_REQUEST = 400;
 * const UNAUTHORIZED = 401;
 * const PAYMENT_REQUIRED = 402;
 * const FORBIDDEN = 403;
 * const NOT_FOUND = 404;
 * const METHOD_NOT_ALLOWED = 405;
 * const NOT_ACCEPTABLE = 406;
 * const PROXY_AUTHENTICATION_REQUIRED = 407;
 * const REQUEST_TIMEOUT = 408;
 * const CONFLICT = 408;
 * const GONE = 410;
 * const LENGTH_REQUIRED = 411;
 * const PRECONDITION_FAILED = 412;
 * const REQUEST_ENTITY_TOO_LARGE = 413;
 * const REQUESTURI_TOO_LARGE = 414;
 * const UNSUPPORTED_MEDIA_TYPE = 415;
 * const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
 * const EXPECTATION_FAILED = 417;
 * const IM_A_TEAPOT = 418;
 * const INTERNAL_SERVER_ERROR = 500;
 * const NOT_IMPLEMENTED = 501;
 * const BAD_GATEWAY = 502;
 * const SERVICE_UNAVAILABLE = 503;
 * const GATEWAY_TIMEOUT = 504;
 * const HTTP_VERSION_NOT_SUPPORTED = 505;
 *
 * @author Rodrigo Andrade
 */
class StatusCode
{
    public const OK = 'HTTP/1.0 200 Ok';
    public const CREATED = 'HTTP/1.0 201 Created';
    public const ACCEPTED = 'HTTP/1.0 202 Accepted';
    public const NONAUTHORITATIVE_INFORMATION = 'HTTP/1.0 203 Non Authoritative Infromation';
    public const NO_CONTENT = 'HTTP/1.0 204 No Content';
    public const RESET_CONTENT = 'HTTP/1.0 205 Reset Content';
    public const PARTIAL_CONTENT = 'HTTP/1.0 206 Partial Content';
    public const MULTIPLE_CHOICES = 'HTTP/1.0 300 Multiple Choices';
    public const MOVED_PERMANENTLY = 'HTTP/1.0 301 Moved Permanently';
    public const MOVED_TEMPORARILY = 'HTTP/1.0 302 Moved Temporarily';
    public const SEE_OTHER = 'HTTP/1.0 303 See Other';
    public const NOT_MODIFIED = 'HTTP/1.0 304 Not Modified';
    public const USE_PROXY = 'HTTP/1.0 305 Use Proxy';
    public const BAD_REQUEST = 'HTTP/1.0 400 Bad Request';
    public const UNAUTHORIZED = 'HTTP/1.0 401 Unauthorized';
    public const PAYMENT_REQUIRED = 'HTTP/1.0 402 Payment Required';
    public const FORBIDDEN = 'HTTP/1.0 403 Forbidden';
    public const NOT_FOUND = 'HTTP/1.0 404 Not Found';
    public const METHOD_NOT_ALLOWED = 'HTTP/1.0 405 Method Not Allowed';
    public const NOT_ACCEPTABLE = 'HTTP/1.0 406 Not Acceptable';
    public const PROXY_AUTHENTICATION_REQUIRED = 'HTTP/1.0 407 Proxy Authentication Required';
    public const REQUEST_TIMEOUT = 'HTTP/1.0 408 Request Timeout';
    public const CONFLICT = 'HTTP/1.0 408 Conflict';
    public const GONE = 'HTTP/1.0 410 Gone';
    public const LENGTH_REQUIRED = 'HTTP/1.0 411 Length Required';
    public const PRECONDITION_FAILED = 'HTTP/1.0 412 Precondition Failed';
    public const REQUEST_ENTITY_TOO_LARGE = 'HTTP/1.0 413 Request Entity Too Large';
    public const REQUESTURI_TOO_LARGE = 'HTTP/1.0 414 Request URI Too Large';
    public const UNSUPPORTED_MEDIA_TYPE = 'HTTP/1.0 415 Unsupported Media Type';
    public const REQUESTED_RANGE_NOT_SATISFIABLE = 'HTTP/1.0 416 Requested Range Not Satisfiable';
    public const EXPECTATION_FAILED = 'HTTP/1.0 417 Expectation Failed';
    public const IM_A_TEAPOT = 'HTTP/1.0 418 Im a Teapot';
    public const INTERNAL_SERVER_ERROR = 'HTTP/1.0 500 Internal Server Error';
    public const NOT_IMPLEMENTED = 'HTTP/1.0 501 Not Implemented';
    public const BAD_GATEWAY = 'HTTP/1.0 502 Bad Gateway';
    public const SERVICE_UNAVAILABLE = 'HTTP/1.0 503 Service Unavailable';
    public const GATEWAY_TIMEOUT = 'HTTP/1.0 504 Gateway Timeout';
    public const HTTP_VERSION_NOT_SUPPORTED = 'HTTP/1.0 505 Http Version Not Supported';
}