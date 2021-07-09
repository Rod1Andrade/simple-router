<?php


namespace Rodri\SimpleRouter\Handlers;

use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

abstract class RouterHandler
{
    protected ?RouterHandler $handler;
    protected String $uri;
    protected String $method;

    public function __construct()
    {
        $this->handler = null;
    }

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