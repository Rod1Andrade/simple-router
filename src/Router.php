<?php


namespace Rodri\SimpleRouter;

use Closure;
use Exception;
use PhpParser\Builder\Class_;
use ReflectionException;
use Rodri\SimpleRouter\Exceptions\ControllerMethodNotFoundException;
use Rodri\SimpleRouter\Handlers\HttpHandler;
use Rodri\SimpleRouter\Handlers\RouterHandler;
use Rodri\SimpleRouter\Helpers\ErrorHelper;
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
        try {
            echo $this->runHandles();
        } catch (ControllerMethodNotFoundException | ReflectionException | Exception $e) {
            echo ErrorHelper::handle($e, $this->debugMode);
        }
    }

    /**
     * Dispatch all grouped routes.
     *
     * @return Response
     * @throws ReflectionException
     */
    public function dispatchGroupRoutes(): Response
    {
        foreach ($this->groupRouter as $groupRouter)
            if ($groupRouter instanceof Router)
                return $groupRouter->baseRouterHandler->handle(new Request());

        return new Response(Response::NONE_VALUE, StatusCode::BAD_REQUEST);
    }

    /**
     * @throws ReflectionException
     */
    public function dispatchBaseHandler(): Response
    {
        if ($this->baseRouterHandler)
            return $this->baseRouterHandler->handle(new Request());
        return new Response(Response::NONE_VALUE, StatusCode::BAD_REQUEST);
    }

    /**
     * Run the appropriate handle or Group or Normal.
     *
     * @return Response
     * @throws ReflectionException
     */
    private function runHandles(): Response
    {
        $groupResponse = $this->dispatchGroupRoutes();
        if ($groupResponse->hasResponseValue())
            return $groupResponse;

        return $this->dispatchBaseHandler();
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
    private function buildURI(string $route): string
    {
        if (isset($this->baseUrl)) {
            $route = $this->baseUrl . $route;
        }

        return $route;
    }
}
