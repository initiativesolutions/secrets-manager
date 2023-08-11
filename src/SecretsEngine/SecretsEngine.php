<?php

namespace SecretsManager\SecretsEngine;

use SecretsManager\Exception\ConfigKeyMissingException;
use SecretsManager\Exception\NoSecretTokenException;
use SecretsManager\FileAccess\FileAccess;
use SecretsManager\SecretsConfig;

class SecretsEngine
{
    protected string $app;
    protected string $env;
    protected string $filePath;

    public function __construct(string $app = "", string $env = "")
    {
        $this->app = $app;
        $this->env = $env;
    }

    public function setFilePath(string $filePath): SecretsEngine
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function getFilePath(): string
    {
        if (empty($this->filePath)) {
            $location = SecretsConfig::get('secrets_files.location');
            $prefix = SecretsConfig::get('secrets_files.prefix');

            if (empty($location)) {
                throw new ConfigKeyMissingException("secrets_files.location");
            }

            $this->filePath = rtrim($location, '/') . "/$prefix{$this->env}_$this->app.json";
        }

        return $this->filePath;
    }

    public function encryptSingleToken(string $token, string $value): void
    {
        $secrets = $this->getTokens();

        $secrets[$token] = Guard::encryptValue($value);

        $this->save($secrets);
    }

    public function encryptJsonFile(string $filePath, bool $removeFile = false): void
    {
        $fileAccess = new FileAccess($filePath);
        $tokens = $fileAccess->readJson();

        $secrets = array_map(fn ($value) => Guard::encryptValue($value), $tokens);

        $this->save($secrets);

        if ($removeFile) {
            $fileAccess->delete();
        }
    }

    public function getTokens(): array
    {
        $secrets = [];
        $fileAccess = new FileAccess($this->getFilePath());

        if ($fileAccess->fileExist()) {
            $secrets = $fileAccess->readJson();
        }

        return $secrets;
    }

    protected function save(array $secrets): void
    {
        (new FileAccess($this->getFilePath()))
            ->setData(json_encode($secrets))
            ->save();
    }

    public function delete(string $token): void
    {
        $secrets = $this->getTokens();

        if (isset($secrets[$token])) {
            unset($secrets[$token]);
            $this->save($secrets);
        } else {
            throw new NoSecretTokenException($token, $this->getFilePath());
        }
    }
}