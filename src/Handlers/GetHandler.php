<?php


namespace Rodri\SimpleRouter\Handlers;


use JetBrains\PhpStorm\Pure;

/**
 * Class GetHandler - Handler to GET HTTP REQUESTS
 * @package Rodri\SimpleRouter\Handlers
 */
class GetHandler extends RouterHandler
{
    #[Pure] public function __construct(string $uri, string $controller, string $method = 'GET')
    {
        parent::__construct(
            uri: $uri,
            method: $method,
            controller: $controller
        );
    }
}