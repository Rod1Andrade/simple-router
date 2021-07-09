<?php


namespace Rodri\SimpleRouter\Handlers;


use JetBrains\PhpStorm\Pure;

/**
 * Class GetHandler - Handler to GET HTTP REQUESTS
 * @package Rodri\SimpleRouter\Handlers
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class HttpHandler extends RouterHandler
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