<?php

require "../vendor/autoload.php";

$router = new \Rodri\SimpleRouter\Router();

# Namespaces Configurations
$router->setControllerNamespace('Rodri\SimpleRouter\Controllers');
$router->setMiddlewareNamespace('Rodri\SimpleRouter\Middlewares');

$router->debug(true);

# Header Router Configurations
$router->headerConfigs([
    'Content-type: application/json'
]);

# Routers
$router->get(['/hello'], 'HelloControllerExample#hello');
$router->get(['/hello/message/:id'], 'HelloControllerExample#helloByMessage');

$router->post(['/post'], 'HelloControllerExample#postTest');

// TODO: Group router and error treatment

# Execution of set router
$router->dispatch();
