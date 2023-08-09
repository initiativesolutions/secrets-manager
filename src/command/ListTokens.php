<?php

namespace SecretsManager\Command;

use SecretsManager\Guard\Retrieve;

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
            throw new \Exception('[app] option is missing !');
        }

        if (!array_key_exists('env', $opts)) {
            throw new \Exception('[env] option is missing !');
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