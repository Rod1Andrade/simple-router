<?php


namespace Rodri\SimpleRouter;

use Closure;
use Exception;
use ReflectionException;
use Rodri\SimpleRouter\Handlers\GroupHttpHandler;
use Rodri\SimpleRouter\Handlers\HttpHandler;
use Rodri\SimpleRouter\Handlers\RouterHandler;

/**
 * Class Router
 * @package Rodri\SimpleRouter
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class Router
{
    # Attributes
    private ?RouterHandler $baseRouterHandler;
    private string $controllerNamespace;
    private bool $debugMode;

    public function __construct()
    {
        $this->baseRouterHandler = null;
        $this->debugMode = false;
    }

    /**
     * @param string $namespace
     */
    public function setControllerNamespace(string $namespace): void
    {
        $this->controllerNamespace = $namespace;
    }

    /**
     * @param string $string
     */
    public function setMiddlewareNamespace(string $string): void
    {
    }

    /**
     * Debug mode true, make the error more understandable.
     * @param bool $value
     */
    public function debug(bool $value)
    {
        $this->debugMode = $value;
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
     * @throws Exception
     */
    public function group(array $routerOptions, Closure $closure): void
    {
    }

    /**
     * GET
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     * @throws Exception
     */
    public function get(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $routerOptions[0],
                $this->concatControllerAndNamespace($controller),
                'GET'
            )
        );
    }

    /**
     * POST
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     * @throws Exception
     */
    public function post(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $routerOptions[0],
                $this->concatControllerAndNamespace($controller),
                'POST'
            )
        );
    }

    /**
     * PUT
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     * @throws Exception
     */
    public function put(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $routerOptions[0],
                $this->concatControllerAndNamespace($controller),
                'PUT'
            )
        );
    }

    /**
     * PATCH
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     * @throws Exception
     */
    public function patch(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $routerOptions[0],
                $this->concatControllerAndNamespace($controller),
                'PATCH'
            )
        );
    }

    /**
     * DELETE
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param String $controller Controller by pattern Controller#method
     * @throws Exception
     */
    public function delete(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $routerOptions[0],
                $this->concatControllerAndNamespace($controller),
                'DELETE'
            )
        );
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
        return $this->controllerNamespace . '\\' . $controller;
    }

}
