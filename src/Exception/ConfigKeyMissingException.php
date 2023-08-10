<?php

namespace SecretsManager\Exception;

class ConfigKeyMissingException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct("key not found in config.yaml [$key]");
    }
}