<?php

require "../vendor/autoload.php";

$router = new \Rodri\SimpleRouter\Router();

# Namespaces Configurations
$router->setControllerNamespace('Rodri\SimpleRouter\Controllers');
$router->setMiddlewareNamespace('Rodri\SimpleRouter\Middlewares');

# Header Router Configurations
$router->headerConfigs([
    'Content-type: application/json'
]);

# Routers
$router->get(['/hello'], 'HelloController#hello');

# Execution of set router
$router->dispatch();
