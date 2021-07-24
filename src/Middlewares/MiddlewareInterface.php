<?php


namespace Rodri\SimpleRouter\Middlewares;


use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

/**
 * Interface Middleware
 * @package Rodri\SimpleRouter\Middlewares
 * @author Rodrigo Andrade
 */
interface MiddlewareInterface
{
    /**
     * Operate in request and return True in success case.
     * @param Request $request
     * @return bool|Response TRUE in success case and Response otherwise.
     */
    public function run(Request $request): bool|Response;
}