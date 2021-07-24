<?php


namespace Rodri\SimpleRouter\utils;


class Message
{

    private static ?Message $instance = null;

    # Attributes
    private array $errorMessages;

    public const ERROR_CONTROLLER_NOT_FOUND = 0;
    public const ERROR_CONTROLLER_METHOD_INVOCATION = 1;

    public const ERROR_MIDDLEWARE_NOT_FOUND = 3;
    public const ERROR_MIDDLEWARE_RUN_INVOCATION = 4;

    private function __construct()
    {
        $this->init();
    }

    /**
     * Singleton instance of Message
     * @return Message
     */
    public static function getInstance(): Message
    {
        if(!self::$instance)
            self::$instance = new Message();

        return self::$instance;
    }

    /**
     * Init resources
     */
    private function init(): void
    {
        $this->errorMessages = [
            Message::ERROR_CONTROLLER_NOT_FOUND => 'Check if the controller namespace is defined and if has a correspond method.',
            Message::ERROR_CONTROLLER_METHOD_INVOCATION => 'Is not possible invoke the controller method, maybe some param is expected.',
            Message::ERROR_MIDDLEWARE_RUN_INVOCATION => 'Is not possible invoke the Middleware run method, maybe some param is expected.. or you not implements the Middleware Interface.'
        ];
    }

    /**
     * @param int $errorCode
     * @return string
     */
    public static function getError(int $errorCode): string
    {
        $message = self::getInstance();
        if(!isset($message->errorMessages[$errorCode]))
            return 'Not defined';

        return $message->errorMessages[$errorCode];
    }
}