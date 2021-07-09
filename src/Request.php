<?php


namespace Rodri\SimpleRouter;


use JetBrains\PhpStorm\Pure;

class Request
{
    public const PARAM_SEPARATOR = ':';

    private function uriRequest()
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function methodRequest()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    #[Pure] public function method(string $method): bool
    {
        return $method == $this->methodRequest();
    }

    /**
     * Check if a uri from a handle is equals a uri from server
     * request, and split the expected params.
     * @param string $uri
     * @param array $params
     * @return bool
     */
    public function uri(string $uri, array &$params = []): bool
    {
        # Split by the bar
        $uriSplit = explode('/', $uri);
        $requestSplit = explode('/', $this->uriRequest());

        if(count($uriSplit) != count($requestSplit))
            return false;

        for($i = 0; $i < count($uriSplit); $i++) {
            # Search the separator to get a param
            if(str_contains($uriSplit[$i], Request::PARAM_SEPARATOR)) {
                $params[$uriSplit[$i]] = $requestSplit[$i];
                continue;
            }

            # If the uri and serve uir is different
            if($uriSplit[$i] != $requestSplit[$i])
                return false;
        }

        return true;
    }
}