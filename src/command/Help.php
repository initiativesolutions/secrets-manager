<?php

namespace SecretsManager\Command;

class Help implements CommandInterface
{

    private CommandLine $cli;
    private array $commandsHelp = [
        "encrypt [TOKEN_NAME] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]",
        "encrypt -file [PATH_TO_JSON_TOKENS] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME] [--remove-file]",
        "rotate",
        "delete [TOKEN_NAME] -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]",
        "list -app [APPLICATION_NAME] -env [ENVIRONNEMENT_NAME]",
    ];

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $this->cli->info("Below commands available :");
        foreach ($this->commandsHelp as $help) {
            $this->cli->write("- $help");
        }
    }
}