<?php

namespace SecretsManager\Command;

use SecretsManager\Exception\CommandIncompleteException;
use SecretsManager\Exception\CommandOptionMissingException;
use SecretsManager\SecretsEngine\SecretsEngine;
use SecretsManager\SecurityKey\KeyVault;

class Encryption implements CommandInterface
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

        if (empty($args) && empty($opts['file'])) {
            throw new CommandIncompleteException("Token name (as an argument) or file (as --file option) not found !");
        }

        $engine = new SecretsEngine($opts['app'], $opts['env']);

        if (!empty($opts['file'])) {
            $engine->encryptJsonFile($opts['file'], isset($opts['remove-file']));
        } else {
            $token = array_shift($args);
            $value = $this->cli->read("Set value for [$token] : ");
            $engine->encryptSingleToken($token, $value);
        }

        $this->cli->success("Success! Secrets saved here [{$engine->getFilePath()}]");

        $secretKeyPath = (new KeyVault())->getKeyFilePath();

        $this->cli->info("With secret key [$secretKeyPath]");
    }

}