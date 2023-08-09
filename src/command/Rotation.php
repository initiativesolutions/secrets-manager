<?php

namespace SecretsManager\Command;

use SecretsManager\Guard\Rotate;

class Rotation implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $files = (new Rotate())->rotate();

        $this->cli->success("The rotation was successful");
        $this->cli->write("The secret key has been re-generated");

        foreach ($files as $file) {
            $this->cli->write("- has been re-encrypted : [$file]");
        }
    }
}