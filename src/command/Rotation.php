<?php

namespace SecretsManager\Command;

class Rotation implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        // TODO: Implement run() method.
    }
}