<?php

namespace SecretsManager\Command;

use SecretsManager\Key\SecretKey;

class KeyGenerator implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $filePath = (new SecretKey())->generate();

        $this->cli->success("The secret key has been generated [$filePath]");
    }

}