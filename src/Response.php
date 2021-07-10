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

    public function __toString(): string
    {
        if($this->response != null)
            return json_encode($this->response);

        return json_encode('');
    }
}