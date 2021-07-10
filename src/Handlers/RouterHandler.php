<?php


namespace Rodri\SimpleRouter\Handlers;

use ReflectionException;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;
use Rodri\SimpleRouter\Helpers\ControllerMethod;
use Rodri\SimpleRouter\Helpers\StatusCode;

/**
 * Class RouterHandler
 * @package Rodri\SimpleRouter\Handlers
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
abstract class RouterHandler
{
    protected ?RouterHandler $handler;

    /**
     * Execute the request and return a response to expected
     * handle.
     * @param Request $request
     * @return Response
     * @throws ReflectionException
     */
    public abstract function handle(Request $request): Response;

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
}
