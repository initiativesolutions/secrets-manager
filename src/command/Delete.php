<?php

namespace SecretsManager\Command;

use SecretsManager\Guard\Delete as DeleteGuard;

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
            throw new \Exception('[app] option is missing !');
        }

        if (!array_key_exists('env', $opts)) {
            throw new \Exception('[env] option is missing !');
        }

        if (empty($args)) {
            throw new \Exception('Token name is missing !');
        }

        $token = array_shift($args);
        $delete = (new DeleteGuard($opts['app'], $opts['env']));
        $delete->delete($token);

        $this->cli->success("Token [$token] has been deleted from [{$delete->getFilePath()}]");
    }
}