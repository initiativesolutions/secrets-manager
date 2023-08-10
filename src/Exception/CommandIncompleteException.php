<?php

namespace SecretsManager\Exception;

class CommandIncompleteException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}