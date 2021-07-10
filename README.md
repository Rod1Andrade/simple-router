# Rodri\SimpleRouter

<img src="https://img.shields.io/badge/php-%5E8.0-blue">

Router to API applications is a simple router build to make easier
and faster the process of set router. 

## How it works?

```php
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

```

# License

MIT
