<?php

namespace SecretsManager\Actions;

use SecretsManager\SecretsCommandLine;
use SecretsManager\SecretsConfig;
use SecretsManager\SecretsStorage;

class SecretsEncryption implements SecretsActionInterface
{

    private SecretsCommandLine $cli;

    public function __construct(SecretsCommandLine $cli)
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

        if (empty($args) && empty($opts['file'])) {
            throw new \Exception('Token name (as an argument) or file (as --file option) not found !');
        }

        $filePath = $this->getSecretFilePath($opts['app'], $opts['env']);

        $storage = (new SecretsStorage())
            ->setFilePath($filePath);

        $secrets = [];

        if ($storage->fileExist()) {
            $secrets = $storage->readJson();
            // todo : in progress :)
        }

        $this->cli->info("Encrypt work [location = $filePath]");
    }

    public function getSecretFilePath(string $app, string $env): string
    {
        $location = SecretsConfig::get('secrets_files.location');
        $prefix = SecretsConfig::get('secrets_files.prefix');

        if (empty($location)) {
            return '';
        }

        return rtrim($location, '/') . "/$prefix{$env}_$app.json";
    }

}