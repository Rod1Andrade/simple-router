<?php


namespace Rodri\SimpleRouter\Helpers;

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
     * @param RuntimeException $runtimeException
     * @param bool $debugMode
     * @return Response
     */
    public static function handle(RuntimeException $runtimeException, bool $debugMode = false): Response
    {
        if($debugMode)
            return new Response([
                'Mode' => 'Debug',
                'error' => 'ControllerMethodNotFoundException',
                'message' => $runtimeException->getMessage(),
                'line' => $runtimeException->getLine(),
                'file' => $runtimeException->getFile(),
                'trace' => $runtimeException->getTrace()
            ], StatusCode::INTERNAL_SERVER_ERROR);

        return new Response(Response::INVALID_RESPONSE, StatusCode::INTERNAL_SERVER_ERROR);
    }
}