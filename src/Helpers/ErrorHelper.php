<?php


namespace Rodri\SimpleRouter\Helpers;

use Exception;
use Rodri\SimpleRouter\Response;
use RuntimeException;

/**
 * Class ErrorHelper
 * @package Rodri\SimpleRouter\Helpers
 * @version 1.0.0
 */
class ErrorHelper
{
    /**
     * @param RuntimeException|Exception $exception
     * @param bool $debugMode
     * @return Response
     */
    public static function handle(RuntimeException|Exception $exception, bool $debugMode = false): Response
    {
        if($debugMode)
            return new Response([
                'Mode' => 'Debug',
                'error' => 'ControllerMethodNotFoundException',
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'trace' => $exception->getTrace()
            ], StatusCode::INTERNAL_SERVER_ERROR);

        return new Response(Response::INVALID_RESPONSE, StatusCode::INTERNAL_SERVER_ERROR);
    }
}