<?php

namespace SecretsManager\Exception;

class NoSecretTokenException extends \Exception
{
    public function __construct(string $token, string $path)
    {
        parent::__construct("Can't delete token ! [$token] not exist here [$path]");
    }
}