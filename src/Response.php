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
        return $this->response != null;
    }

    public function __toString(): string
    {
        if($this->response != null)
            return json_encode($this->response);

        return json_encode('');
    }
}