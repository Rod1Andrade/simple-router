<?php


namespace Rodri\SimpleRouter;


class Response
{
    public function __construct(private array $response)
    {
    }

    public function __toString(): string
    {
        return json_encode($this->response);
    }
}