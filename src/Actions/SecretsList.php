<?php

namespace SecretsManager\Actions;

use SecretsManager\SecretsCommandLine;

class SecretsList implements SecretsActionInterface
{

    private SecretsCommandLine $cli;

    public function __construct(SecretsCommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        // TODO: Implement run() method.
    }
}