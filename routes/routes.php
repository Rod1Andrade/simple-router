<?php
/**
 * Router file - Here you can set all your application routes.
 * Remember to change debug value to false if not in development mode.
 *
 * @author Rodrigo Andrade
 * @since 2021-07-10
 * @version 1.0.0
 */

use Rodri\SimpleRouter\Router;

$router = new Router();

# Namespaces Configurations
$router->setControllerNamespace('Rodri\SimpleRouter\Controllers');
$router->setMiddlewareNamespace('Rodri\SimpleRouter\Middlewares');

# Debug mode
$router->debug(true);

# Header Router Configurations
$router->headerConfigs([
    'Content-type: application/json'
]);

# Routers without group
$router->get(['/hello'], 'HelloControllerExample#hello');
$router->get(['/hello/message/:id'], 'HelloControllerExample#helloByMessage');
$router->post(['/post'], 'HelloControllerExample#postTest');

# Router with group
$router->group(['/group/test'], function (Router $router) {
    $router->get([''], 'HelloControllerExample#hello');
    $router->get(['/:id'], 'HelloControllerExample#helloByMessage');
    $router->post([''], 'HelloControllerExample#postTest');
});

$router->group(['/group'], function (Router $router) {
    $router->get([''], 'HelloControllerExample#hello');
    $router->get(['/:id'], 'HelloControllerExample#helloByMessage');
    $router->post([''], 'HelloControllerExample#postTest');
});

# Execution of set router
$router->dispatch();