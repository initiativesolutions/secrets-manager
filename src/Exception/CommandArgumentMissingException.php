<?php

namespace SecretsManager\Exception;

class CommandArgumentMissingException extends \Exception
{
    public function __construct(string $argumentName)
    {
        parent::__construct("[$argumentName] argument is missing!");
    }
}