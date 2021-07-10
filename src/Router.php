<?php


namespace Rodri\SimpleRouter;

use Closure;
use Exception;
use ReflectionException;
use Rodri\SimpleRouter\Exceptions\ControllerMethodNotFoundException;
use Rodri\SimpleRouter\Exceptions\SimpleRouterException;
use Rodri\SimpleRouter\Handlers\GroupHttpHandler;
use Rodri\SimpleRouter\Handlers\HttpHandler;
use Rodri\SimpleRouter\Handlers\RouterHandler;
use Rodri\SimpleRouter\Helpers\StatusCode;

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
     */
    public function dispatch()
    {
        try {
            echo $this->baseRouterHandler->handle(new Request());
        } catch (ControllerMethodNotFoundException|ReflectionException $e) {
            if ($this->debugMode) {
                echo new Response([
                    'Mode' => 'Debug',
                    'error' => 'ControllerMethodNotFoundException',
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTrace()
                ], StatusCode::INTERNAL_SERVER_ERROR);
            } else {
                echo new Response(null, StatusCode::INTERNAL_SERVER_ERROR);
            }
        }

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
