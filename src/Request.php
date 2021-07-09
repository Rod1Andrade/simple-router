<?php


namespace Rodri\SimpleRouter;


use JetBrains\PhpStorm\Pure;

class Request
{
    private function uriRequest()
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function methodRequest()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    #[Pure] public function method(string $method): bool
    {
        return $method == $this->methodRequest();
    }

    #[Pure] public function uri(string $uri): bool
    {
        return $uri == $this->uriRequest();
    }
}