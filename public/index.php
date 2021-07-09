<?php

require "../vendor/autoload.php";

$router = new \Rodri\SimpleRouter\Router();

header('Content-type: application/json');

$router->get(['/hello']);
$router->get(['/hello/world']);
$router->get(['/hello/universe']);

$router->post(['/hello']);
$router->put(['/hello']);
$router->patch(['/hello']);
$router->delete(['/hello']);

$router->dispatch();
