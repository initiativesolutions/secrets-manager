<?php

namespace SecretsManager\Command;

use SecretsManager\Exception\CommandOptionMissingException;
use SecretsManager\SecretsEngine\SecretsEngine;

class ListTokens implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $opts = $this->cli->getOpts();

        if (!array_key_exists('app', $opts)) {
            throw new CommandOptionMissingException("app");
        }

        if (!array_key_exists('env', $opts)) {
            throw new CommandOptionMissingException("env");
        }

        $engine = new SecretsEngine($opts['app'], $opts['env']);
        $tokens = $engine->getTokens();

        $this->cli->info("Below the list of all tokens in [{$engine->getFilePath()}]");

        foreach ($tokens as $token => $value) {
            $this->cli->write("- $token");
        }

        $this->cli->success(count($tokens) . " tokens listed.");
    }
}