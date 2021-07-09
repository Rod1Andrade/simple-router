<?php


namespace Rodri\SimpleRouter\Handlers;


use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

/**
 * Class GetHandler - Handler to GET HTTP REQUESTS
 * @package Rodri\SimpleRouter\Handlers
 */
class PostHandler extends RouterHandler
{
    public function __construct(String $uri, String $method = 'POST')
    {
        $this->uri = $uri;
        $this->method = $method;
    }

   public function handle(Request $request): Response
    {
        if($request->uri($this->uri) && $request->method($this->method)) {
            return new Response(['message' => "Hello from PostHandle: $this->uri"]);
        }

        return $this->getHandler()->handle($request);
    }
}