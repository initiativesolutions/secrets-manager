<?php

namespace SecretsManager\Exception;

class AlgorithmNotSupportedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unknown algorithm [in config.yaml]. For a list of supported algorithms visit: (https://secure.php.net/manual/en/function.openssl-get-cipher-methods.php)");
    }
}