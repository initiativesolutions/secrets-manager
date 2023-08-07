<?php

namespace SecretsManager\Actions;

use SecretsManager\SecretsCommandLine;
use SecretsManager\SecretsConfig;
use SecretsManager\SecretsStorage;

class SecretsKeyGenerator implements SecretsActionInterface
{

    private SecretsCommandLine $cli;

    public function __construct(SecretsCommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $filePath = $this->getKeyFilePath();

        if (empty($filePath)) {
            throw new \Exception("Location for saving secret key is empty from config.yaml [missing locations.encryption_key]");
        }

        $encryptionKey = bin2hex(random_bytes(32));

        (new SecretsStorage())
            ->setData($encryptionKey)
            ->setFilePath($filePath)
            ->save();

        $this->cli->success("The secret key has been generated [$filePath]");
    }

    public function getKeyFilePath(): string
    {
        $location = SecretsConfig::get('encryption_key.location');
        $filename = SecretsConfig::get('encryption_key.file_name');

        if (empty($location) || empty($filename)) {
            return '';
        }

        return rtrim($location, '/') . '/' . ltrim($filename, '/');
    }

}