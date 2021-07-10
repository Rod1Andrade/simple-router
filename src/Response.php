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
    public const NONE_VALUE = 'NONE_VALUE_NULL';

    public function __construct(
        private mixed $response,
        String $statusCode = StatusCode::OK,
    )
    {
        header($statusCode);
    }

    /**
     * Check if has some response value.
     * @return bool
     */
    public function hasResponseValue(): bool
    {
        return $this->response !== Response::NONE_VALUE;
    }

    public function __toString(): string
    {
        if($this->hasResponseValue())
            return json_encode($this->response);

        return json_encode('');
    }
}