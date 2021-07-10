<?php


namespace Rodri\SimpleRouter\utils;


use PhpParser\Node\Scalar\String_;

class Message
{

    private static ?Message $instance = null;

    # Attributes
    private array $errorMessages;
    public const ERROR_CONTROLLER_NOT_FOUND = 0;
    public const ERROR_CONTROLLER_METHOD_INVOCATION = 1;

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
            Message::ERROR_CONTROLLER_METHOD_INVOCATION => 'Is not possible invoke the controller method, maybe some param is expected.'
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