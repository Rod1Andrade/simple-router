<?php


namespace Rodri\SimpleRouter;

use Closure;
use Exception;
use JetBrains\PhpStorm\Pure;
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
 * @version 1.1.1
 */
class Router
{
    private const INDEX_ROUTER_MIDDLEWARE = 'middleware';

    private ?RouterHandler $baseRouterHandler;

    private string $controllerNamespace;
    private string $middlewareNamespace;

    private bool $debugMode;

    private ?string $groupBaseUrl = null;
    private array|string $groupBaseMiddleware = [];

    private array $groupRouters;

    public function __construct()
    {
        $this->baseRouterHandler = null;
        $this->debugMode = false;
        $this->groupRouters = array();
    }

    /**
     * @param string $namespace
     */
    public function setControllerNamespace(string $namespace): void
    {
        $this->controllerNamespace = $namespace;
    }

    /**
     * @param string $namespace
     */
    public function setMiddlewareNamespace(string $namespace): void
    {
        $this->middlewareNamespace = $namespace;
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
        $this->groupRouters[] = $router;
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
        $router->groupBaseUrl = $routerOptions[0];
        $router->groupBaseMiddleware = $routerOptions[self::INDEX_ROUTER_MIDDLEWARE] ?? [];
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
                'GET',
                $this->getMiddlewares($routerOptions)
            )
        );
    }

    /**
     * POST
     * @param array $routerOptions Router options [0] => '/router' ['middleware' => Middleware]
     * @param String $controller Controller by pattern Controller#method
     */
    public function post(array $routerOptions, string $controller): void
    {
        $this->addRouterHandler(
            new HttpHandler(
                $this->buildURI($routerOptions[0]),
                $this->concatControllerAndNamespace($controller),
                'POST',
                $this->getMiddlewares($routerOptions)
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
                'PUT',
                $this->getMiddlewares($routerOptions)
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
                'PATCH',
                $this->getMiddlewares($routerOptions)
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
                'DELETE',
                $this->getMiddlewares($routerOptions)
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
        foreach ($this->groupRouters as $groupRouter) {
            if ($groupRouter instanceof Router) {
                $response = $groupRouter->baseRouterHandler->handle(new Request());

                if ($response->hasInvalidResponse())
                    return $response;
            }
        }
        return new Response(Response::INVALID_RESPONSE, StatusCode::BAD_REQUEST);
    }

    /**
     * @throws ReflectionException
     */
    public function dispatchBaseHandler(): Response
    {
        if ($this->baseRouterHandler)
            return $this->baseRouterHandler->handle(new Request());
        return new Response(Response::INVALID_RESPONSE, StatusCode::BAD_REQUEST);
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
        if ($groupResponse->hasInvalidResponse())
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
     * @param string $middleware
     * @return string
     */
    private function concatMiddlewareAndNamespace(string $middleware): string
    {
        return $this->middlewareNamespace . '\\' . $middleware;
    }

    /**
     * @param array $routerOptions
     * @return array
     */
    #[Pure] private function getMiddlewares(array $routerOptions): array
    {
        # Middlewares of group
        $groupMiddlewares = [];
        if (!empty($this->groupBaseMiddleware) && is_string($this->groupBaseMiddleware)) {
            $groupMiddlewares[] = $this->concatMiddlewareAndNamespace($this->groupBaseMiddleware);
        }

        if (!empty($this->groupBaseMiddleware) && is_array($this->groupBaseMiddleware)) {
            foreach ($this->groupBaseMiddleware as $middlewareItem) {
                $groupMiddlewares[] = $this->concatMiddlewareAndNamespace($middlewareItem);
            }
        }

        # If just exist the middleware of group return it.
        if (!isset($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE])) {
            return $groupMiddlewares;
        }

        # If not, merge them
        if (is_string($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE])) {
            $middleware = empty($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE]) ? [] : [$this->concatMiddlewareAndNamespace($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE])];
            return array_merge($groupMiddlewares, $middleware);
        }

        if (is_array($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE])) {
            $middlewares = [];
            foreach ($routerOptions[Router::INDEX_ROUTER_MIDDLEWARE] as $middlewareItem) {
                $middlewares[] = $this->concatMiddlewareAndNamespace($middlewareItem);
            }

            return array_merge($groupMiddlewares, $middlewares);
        }

        return [];
    }

    /**
     * Build a URI with a base URL if exist
     * @param String $route
     * @return String
     */
    private function buildURI(string $route): string
    {
        if (isset($this->groupBaseUrl)) {
            $route = $this->groupBaseUrl . $route;
        }

        return $route;
    }
}
