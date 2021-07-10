<?php


namespace Rodri\SimpleRouter;

use Closure;
use Exception;
use PhpParser\Builder\Class_;
use ReflectionException;
use Rodri\SimpleRouter\Exceptions\ControllerMethodNotFoundException;
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
    private ?string $baseUrl = null;

    private array $groupRouter;

    public function __construct()
    {
        $this->baseRouterHandler = null;
        $this->debugMode = false;
        $this->groupRouter = array();
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
     * Add a new Router to group array
     * @param Router $router
     */
    public function addRouterGroup(Router $router): void
    {
        $this->groupRouter[] = $router;
    }

    /**
     * Group the routes to work with the same base URI. The route in group
     * can have the same middleware defined.
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware
     * @param Closure $closure function(Router $router)
     */
    public function group(array $routerOptions, Closure $closure): void
    {
        $router = clone $this;
        $router->baseUrl = null;
        $router->baseUrl = $routerOptions[0];

        # Pass to closure function a instate of route
        $closure($router);

        $this->addRouterGroup($router);
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
                $this->buildURI($routerOptions[0]),
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
                $this->buildURI($routerOptions[0]),
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
                $this->buildURI($routerOptions[0]),
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
                $this->buildURI($routerOptions[0]),
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
                $this->buildURI($routerOptions[0]),
                $this->concatControllerAndNamespace($controller),
                'DELETE'
            )
        );
    }

    /**
     * Dispatch to router work call the expected handle.
     */
    public function dispatch(): void
    {

        # If have router grouped routes
        if($this->dispatchGroupRoutes()) return;

        try {
            echo $this->baseRouterHandler->handle(new Request());
        } catch (ControllerMethodNotFoundException | ReflectionException $e) {
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
     * Dispatch all grouped routes.
     *
     * @return bool
     */
    private function dispatchGroupRoutes(): bool
    {
        foreach ($this->groupRouter as $groupRouter) {
            if ($groupRouter instanceof Router) {
                $response = $groupRouter->baseRouterHandler->handle(new Request());
                if($response->hasResponseValue()) {
                    echo $response;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $controller
     * @return string
     */
    private function concatControllerAndNamespace(string $controller): string
    {
        return $this->controllerNamespace . '\\' . $controller;
    }

    /**
     * Build a URI with a base URL if exist
     * @param String $route
     * @return String
     */
    private function buildURI(String $route): String
    {
        if(isset($this->baseUrl)) {
            $route = $this->baseUrl.$route;
        }

        return $route;
    }
}
