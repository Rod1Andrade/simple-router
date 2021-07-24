<?php


namespace Rodri\SimpleRouter\Helpers;


use ReflectionException;
use ReflectionMethod;
use Rodri\SimpleRouter\Exceptions\ControllerMethodNotFoundException;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;
use Rodri\SimpleRouter\utils\Message;

/**
 * Class ControllerMethod
 * @package Rodri\SimpleRouter\utils
 * @author Rodrigo Andrade
 * @version 1.0.0
 */
class ControllerMethod
{
    private ReflectionMethod $reflectionMethod;
    private String $controller;

    /**
     * Instantiate a new Class of Controller execute appropriate method
     * @param Request $request
     * @return Response|null
     */
    public function call(Request $request): ?Response
    {
        try {
            return $this->reflectionMethod->invoke(new $this->controller, $request);
        } catch (ReflectionException) {
            throw new ControllerMethodNotFoundException(Message::getError(Message::ERROR_CONTROLLER_METHOD_INVOCATION));
        }
    }

    /**
     * Build a new instance of ControllerMethod
     */
    public static function build(String $controller): ControllerMethod
    {
        $controllerMethod = new ControllerMethod();

        $controller = explode('#', $controller);

        try {
            $controllerMethod->reflectionMethod = new ReflectionMethod($controller[0], $controller[1]);
        } catch (ReflectionException) {
            throw new ControllerMethodNotFoundException(Message::getError(Message::ERROR_CONTROLLER_NOT_FOUND));
        }

        $controllerMethod->controller = $controller[0];

        return $controllerMethod;
    }
}