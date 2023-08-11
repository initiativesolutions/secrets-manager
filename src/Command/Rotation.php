<?php

namespace SecretsManager\Command;

use SecretsManager\SecretsEngine\Rotate;

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

        $this->cli->success("The rotation completed successfully.");
        $this->cli->write("The secret key has been regenerated");

        foreach ($files as $file) {
            $this->cli->write("- has been re-encrypted : [$file]");
        }
    }
}