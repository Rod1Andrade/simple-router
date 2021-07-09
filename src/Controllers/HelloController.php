<?php


namespace Rodri\SimpleRouter\Controllers;


use JetBrains\PhpStorm\Pure;
use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;

class HelloController
{
    #[Pure] public function hello(): Response
    {
        return new Response(['message' => 'Hello world']);
    }
}