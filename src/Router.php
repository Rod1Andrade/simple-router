<?php


namespace Rodri\SimpleRouter;

use Exception;
use Rodri\SimpleRouter\Handlers\DeleteHandler;
use Rodri\SimpleRouter\Handlers\GetHandler;
use Rodri\SimpleRouter\Handlers\PatchHandler;
use Rodri\SimpleRouter\Handlers\PostHandler;
use Rodri\SimpleRouter\Handlers\PutHandler;
use Rodri\SimpleRouter\Handlers\RouterHandler;

class Router
{
    private ?RouterHandler $baseRouterHandler;

    public function __construct()
    {
        $this->baseRouterHandler = null;
    }

    /**
     * Add a router handler to chain of responsibility
     * @throws Exception
     */
    public function addRouterHandler(RouterHandler $routerHandler): void
    {
        if($this->baseRouterHandler == null) {
            $this->baseRouterHandler = $routerHandler;
            $this->baseRouterHandler->setNext(null);
        } else {
            $routerHandler->setNext($this->baseRouterHandler);
            $this->baseRouterHandler = $routerHandler;
        }
    }

    public function get(array $routerOptions): void
    {
        try {
            $this->addRouterHandler(new GetHandler($routerOptions[0]));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function post(array $routerOptions): void
    {
        try {
            $this->addRouterHandler(new PostHandler($routerOptions[0]));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function put(array $routerOptions): void
    {
        try {
            $this->addRouterHandler(new PutHandler($routerOptions[0]));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function patch(array $routerOptions): void
    {
        try {
            $this->addRouterHandler(new PatchHandler($routerOptions[0]));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function delete(array $routerOptions): void
    {
        try {
            $this->addRouterHandler(new DeleteHandler($routerOptions[0]));
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function dispatch()
    {
        echo $this->baseRouterHandler->handle(new Request());
        flush();
    }
}