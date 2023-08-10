<?php

namespace SecretsManager\Exception;

class CommandOptionMissingException extends \Exception
{
    public function __construct(string $optionName)
    {
        parent::__construct("[$optionName] option is missing!");
    }
}