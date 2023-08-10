<?php

namespace SecretsManager\Command;

use SecretsManager\Engine\Delete as DeleteEngine;
use SecretsManager\Exception\CommandArgumentMissingException;
use SecretsManager\Exception\CommandOptionMissingException;

class Delete implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $args = $this->cli->getArgs();
        $opts = $this->cli->getOpts();

        if (!array_key_exists('app', $opts)) {
            throw new CommandOptionMissingException("app");
        }

        if (!array_key_exists('env', $opts)) {
            throw new CommandOptionMissingException("env");
        }

        if (empty($args)) {
            throw new CommandArgumentMissingException("[TOKEN_NAME]");
        }

        $token = array_shift($args);
        $delete = (new DeleteEngine($opts['app'], $opts['env']));
        $delete->delete($token);

        $this->cli->success("Token [$token] has been deleted from [{$delete->getFilePath()}]");
    }
}