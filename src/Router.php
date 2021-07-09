<?php


namespace Rodri\SimpleRouter;

use Exception;
use ReflectionException;
use Rodri\SimpleRouter\Handlers\GetHandler;
use Rodri\SimpleRouter\Handlers\RouterHandler;

class Router
{
    private ?RouterHandler $baseRouterHandler;
    private string $controllerNamespace;

    public function __construct()
    {
        $this->baseRouterHandler = null;
    }

    public function setControllerNamespace(string $namespace): void
    {
        $this->controllerNamespace = $namespace;
    }

    public function setMiddlewareNamespace(string $string): void
    {
    }

    /**
     * Set the header router configs
     * @param array $configs
     */
    public function headerConfigs(array $configs)
    {
        foreach ($configs as $config) {
            header($config);
        }
    }

    /**
     * Add a router handler to chain of responsibility
     * @param RouterHandler $routerHandler
     * @throws Exception
     */
    public function addRouterHandler(RouterHandler $routerHandler): void
    {
        if ($this->baseRouterHandler == null) {
            $this->baseRouterHandler = $routerHandler;
            $this->baseRouterHandler->setNext(null);
        } else {
            $routerHandler->setNext($this->baseRouterHandler);
            $this->baseRouterHandler = $routerHandler;
        }
    }

    /**
     * GET
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     */
    public function get(array $routerOptions, string $controller): void
    {
        try {
            $this->addRouterHandler(
                new GetHandler($routerOptions[0], $this->concatControllerAndNamespace($controller))
            );
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * Dispatch to router work call the expected handle.
     * @throws ReflectionException
     */
    public function dispatch()
    {
        echo $this->baseRouterHandler->handle(new Request());
        flush();
    }

    /**
     * @param string $controller
     * @return string
     */
    private function concatControllerAndNamespace(string $controller): string
    {
        return $this->controllerNamespace .'\\'. $controller;
    }

}