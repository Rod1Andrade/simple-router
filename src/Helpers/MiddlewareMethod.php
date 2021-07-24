<?php


namespace Rodri\SimpleRouter\Helpers;


use ReflectionException;
use ReflectionMethod;
use Rodri\SimpleRouter\Exceptions\ControllerMethodNotFoundException;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;
use Rodri\SimpleRouter\utils\Message;

/**
 * Class MiddlewareMethod
 * @package Rodri\SimpleRouter\utils
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class MiddlewareMethod
{
    private const METHOD_NAME = 'run';
    private ReflectionMethod $reflectionMethod;
    private string $middleware;

    /**
     * Instantiate a new Class of Controller execute appropriate method
     * @param Request $request
     * @return Response|bool|null
     */
    public function call(Request $request): Response|bool|null
    {
        try {
            return $this->reflectionMethod->invoke(new $this->middleware, $request);
        } catch (ReflectionException) {
            throw new ControllerMethodNotFoundException(Message::getError(Message::ERROR_MIDDLEWARE_RUN_INVOCATION));
        }
    }

    /**
     * Build a new instance of ControllerMethod
     */
    public static function build(string $middleware): MiddlewareMethod
    {
        $middlewareMethod = new MiddlewareMethod();

        try {
            $middlewareMethod->reflectionMethod = new ReflectionMethod($middleware, MiddlewareMethod::METHOD_NAME);
        } catch (ReflectionException) {
            throw new ControllerMethodNotFoundException(Message::getError(Message::ERROR_MIDDLEWARE_NOT_FOUND));
        }

        $middlewareMethod->middleware = $middleware;

        return $middlewareMethod;
    }
}