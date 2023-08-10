<?php

namespace SecretsManager\Command;

use SecretsManager\Engine\Retrieve;
use SecretsManager\Exception\CommandOptionMissingException;

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

        $retrieve = new Retrieve($opts['app'], $opts['env']);
        $tokens = $retrieve->getTokens();

        $this->cli->info("Below the list of all tokens in [{$retrieve->getFilePath()}]");

        foreach ($tokens as $token => $value) {
            $this->cli->write("- $token");
        }

        $this->cli->success(count($tokens) . " tokens listed.");
    }
}