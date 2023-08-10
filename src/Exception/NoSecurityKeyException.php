<?php

namespace SecretsManager\Exception;

class NoSecurityKeyException extends \Exception
{

    public function __construct(string $additionalMessage = "")
    {
        $message = ($additionalMessage ? "$additionalMessage\n" : "") .
            "No security key currently generated, You must have already encrypted tokens before";
        parent::__construct($message);
    }

}