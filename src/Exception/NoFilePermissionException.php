<?php

namespace SecretsManager\Exception;

class NoFilePermissionException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}