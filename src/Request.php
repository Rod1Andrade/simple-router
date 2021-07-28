<?php


namespace Rodri\SimpleRouter;


use JetBrains\PhpStorm\Pure;

/**
 * Class Request
 * @package Rodri\SimpleRouter
 * @author Rodrigo Andrade
 * @version 1.3.0
 */
class Request
{
    # Attributes
    private array $params;
    private array $values;

    # Constants
    public const PARAM_SEPARATOR = ':';

    public function __construct()
    {
        $this->params = [];
        $this->values = [];
    }

    /**
     * @return string|bool
     */
    public function body(): string|bool
    {
        return file_get_contents('php://input');
    }

    /**
     * Add a new value to be carried between controllers and middlewares.
     * @param string $name
     * @param mixed $value
     */
    public function addValue(string $name, mixed $value): void
    {
        $this->values[$name] = $value;
    }

    /**
     * Get a value set before to be carried
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name): mixed
    {
        return $this->values[$name] ?? null;
    }
    
    /**
     * Get a value from body request
     * @param string $in
     * @return mixed
     */
    public function input(string $in): mixed
    {
        $associativeBody = json_decode($this->body(), true);

        if (!$associativeBody || !$associativeBody[$in])
            return '';

        return $associativeBody[$in];
    }

    /**
     * Get a router param by named param with separator ':'
     * @param String $param
     * @return mixed
     */
    public function param(string $param): mixed
    {
        return !empty($this->params[$param]) ? $this->params[$param] : null;
    }

    /**
     * Get all params
     * @return array
     */
    public function allParams(): array
    {
        return $this->params;
    }

    /**
     * Get the authorization value or return false if not exists.
     * @return bool|array
     */
    public function authorization(): bool|string
    {
        return $_SERVER['HTTP_AUTHORIZATION'] ?? false;
    }

    #[Pure] public function method(string $method): bool
    {
        return $method == $this->methodRequest();
    }

    /**
     * Check if a uri from a handle is equals a uri from server
     * request, and split the expected params.
     * @param string $uri
     * @return bool
     */
    public function uri(string $uri): bool
    {
        # Split by the bar
        $uriSplit = explode('/', $uri);
        $requestSplit = explode('/', $this->uriRequest());

        if (count($uriSplit) != count($requestSplit))
            return false;

        for ($i = 0; $i < count($uriSplit); $i++) {
            # Search the separator to get a param
            if (str_contains($uriSplit[$i], Request::PARAM_SEPARATOR)) {
                $this->params[$uriSplit[$i]] = $requestSplit[$i];
                continue;
            }

            # If the uri and serve uir is different
            if ($uriSplit[$i] != $requestSplit[$i])
                return false;
        }

        return true;
    }

    /**
     * Server URI Request
     * @return mixed
     */
    private function uriRequest(): mixed
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Server method
     * @return mixed
     */
    private function methodRequest(): mixed
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function __clone(): void
    {
    }

    public function __set(string $name, $value): void
    {
    }

    public function __get(string $name): mixed
    {
        return $this->getValue($name);
    }
}