<?php


namespace Rodri\SimpleRouter\Handlers;

use ReflectionException;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;
use Rodri\SimpleRouter\utils\ControllerMethod;

/**
 * Class RouterHandler
 * @package Rodri\SimpleRouter\Handlers
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
abstract class RouterHandler
{
    protected ?RouterHandler $handler;

    public function __construct(
        protected string $uri,
        protected string $method,
        protected string $controller
    )
    {
    }

    /**
     * Execute the request and return a response to expected
     * handle.
     * @param Request $request
     * @return Response
     * @throws ReflectionException
     */
    public function handle(Request $request): Response
    {
        if($this->validateRouter($request))
            return ControllerMethod::build($this->controller)->call($request);

        return $this->getHandler()->handle($request);
    }

    /**
     * @param RouterHandler|null $handler
     * @return RouterHandler|null
     */
    public function setNext(?RouterHandler $handler): ?RouterHandler
    {
        $this->handler = $handler;
        return $handler;
    }

    /**
     * @return RouterHandler|null
     */
    public function getHandler(): ?RouterHandler
    {
        return $this->handler;
    }

    /**
     * Validate Router by request uri and method
     * @param Request $request
     * @return bool
     */
    private function validateRouter(Request $request): bool
    {
        return $request->uri($this->uri) && $request->method($this->method);
    }
}