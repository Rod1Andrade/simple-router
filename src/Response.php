<?php


namespace Rodri\SimpleRouter;


use Rodri\SimpleRouter\Helpers\StatusCode;

/**
 * Class Response
 * @package Rodri\SimpleRouter
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class Response
{
    public const NONE_RESPONSE = '';
    public const INVALID_RESPONSE = 'NONE_VALUE_NULL';

    public function __construct(
        private mixed $response = Response::NONE_RESPONSE,
        String $statusCode = StatusCode::OK,
    )
    {
        header($statusCode);
    }

    /**
     * Check if has some response inavlid value.
     * @return bool
     */
    public function hasInvalidResponse(): bool
    {
        return $this->response !== Response::INVALID_RESPONSE;
    }

    public function __toString(): string
    {
        if(empty($this->response)) {
            return PHP_EOL;
        }

        if($this->hasInvalidResponse()) {
            return json_encode($this->response);
        }

        return '';
    }
}