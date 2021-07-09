<?php


namespace Rodri\SimpleRouter\utils;


use ReflectionException;
use ReflectionMethod;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

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
     * @throws ReflectionException
     */
    public function call(Request $request): ?Response
    {
        return $this->reflectionMethod->invoke(new $this->controller, $request);
    }

    /**
     * Build a new instance of ContrllerMethod
     * @throws ReflectionException
     */
    public static function build(String $controller): ControllerMethod
    {
        $controllerMethod = new ControllerMethod();

        $controller = explode('#', $controller);

        $controllerMethod->reflectionMethod = new ReflectionMethod($controller[0], $controller[1]);
        $controllerMethod->controller = $controller[0];

        return $controllerMethod;
    }
}