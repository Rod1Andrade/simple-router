<?php


namespace Rodri\SimpleRouter\Handlers;


use Rodri\SimpleRouter\Helpers\ControllerMethod;
use Rodri\SimpleRouter\Helpers\MiddlewareMethod;
use Rodri\SimpleRouter\Helpers\StatusCode;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

/**
 * Class HttpHandler - Handler to HTTP REQUESTS
 * @package Rodri\SimpleRouter\Handlers
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class HttpHandler extends RouterHandler
{
    public function __construct(
        private string $uri,
        private string $controller,
        private string $method = 'GET',
        private array $middleware = [],
    )
    {
    }

    public function handle(Request $request): Response
    {
        if ($this->validateRouter($request)) {

            # Middleware in router Request handler
            if(!empty($this->middleware)) {
                foreach($this->middleware as $middleware) {
                    # Instantiate a middleware
                    $middlewareInstance = MiddlewareMethod::build($middleware);

                    # Run in method
                    $middlewareResponse = $middlewareInstance->call($request);

                    if($middlewareResponse instanceof Response) {
                        return $middlewareResponse;
                    }
                }
            }

            return ControllerMethod::build($this->controller)->call($request);
        }

        if ($this->getHandler())
            return $this->getHandler()->handle($request);

        return new Response(Response::INVALID_RESPONSE, StatusCode::BAD_REQUEST);
    }

    /**
     * Validate Router by request uri and method
     * @param Request $request
     * @return bool
     */
    protected function validateRouter(Request $request): bool
    {
        return $request->uri($this->uri) && $request->method($this->method);
    }
}