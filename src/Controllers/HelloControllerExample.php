<?php


namespace Rodri\SimpleRouter\Controllers;


use Rodri\SimpleRouter\Request;
use Rodri\SimpleRouter\Response;
use Rodri\SimpleRouter\utils\StatusCode;

/**
 * Class HelloController Example
 * @package Rodri\SimpleRouter\Controllers
 */
class HelloControllerExample
{
    private array $messages = [
        'Programming is a passion',
        'Programming is a intellectual work',
        'Programming is a craft of something really beautiful'
    ];

    public function hello(): Response
    {
        return new Response(['message' => 'Hello world']);
    }

    public function helloByMessage(Request $request): Response
    {
        return new Response(['message' => $this->messages[(int) $request->param(':id')]]);
    }

    public function postTest(Request $request): Response
    {
        return new Response([
            'post' => 'Hello from post request',
            'message' => $request->input('inMessage')
        ]);
    }
}