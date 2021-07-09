# Rodri\SimpleRouter

Router to API applications

## How it works?

```php
$router = new Router();

$router->configs([
    'Content-type' => 'application/json'
]);

# Middleware For all routes
$router->middleware('Middleware');

# Grouped Routers
$router->group(['uri', 'middleware' => 'Middleware'], function ($router) {
    $router->get(['/:id'], 'Controller#method');
    $router->post([''], 'Controller#method');
    $router->put(['/:id'], 'Controller#method');
    $router->delete(['/:id'], 'Controller#method');
});

# Normal Call
$router->get(['/user/:id', 'middleware' => 'Middleware'], 'Controller#method');
$router->post(['/user'], 'Controller#method');
$router->put(['/user/:id'], 'Controller#method');
$router->delete(['/user/:id'], 'Controller#method');

```
