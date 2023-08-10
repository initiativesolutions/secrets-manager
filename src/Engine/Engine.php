<?php

namespace SecretsManager\Engine;

use SecretsManager\Exception\ConfigKeyMissingException;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\FileAccess\WriteFiles;
use SecretsManager\SecretsConfig;

abstract class Engine
{
    protected string $app;
    protected string $env;
    protected string $filePath;

    public function __construct(string $app = "", string $env = "")
    {
        $this->app = $app;
        $this->env = $env;
    }

    public function setFilePath(string $filePath): Engine
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

    protected function readJsonSecrets(): array
    {
        $secrets = [];
        $read = (new ReadFiles())->setFilePath($this->getFilePath());

        if ($read->fileExist()) {
            $secrets = $read->readJson();
        }

        return $secrets;
    }

    protected function saveSecrets(array $secrets): void
    {
        (new WriteFiles())
            ->setFilePath($this->getFilePath())
            ->setData(json_encode($secrets))
            ->save();
    }
}