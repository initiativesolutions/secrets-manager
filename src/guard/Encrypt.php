<?php

namespace SecretsManager\Guard;

use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\FileAccess\WriteFiles;
use SecretsManager\Key\SecretKey;
use SecretsManager\SecretsConfig;

class Encrypt
{

    protected string $app;
    protected string $env;
    protected string $filePath;

    public function __construct(string $app, string $env)
    {
        $this->app = $app;
        $this->env = $env;

        $this->filePath = $this->getSecretFilePath($app, $env);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function encryptSingleToken(string $token, string $value)
    {
        $secrets = [];
        $read = (new ReadFiles())->setFilePath($this->filePath);

        if ($read->fileExist()) {
            $secrets = $read->readJson();
        }

        $secrets[$token] = $this->encryptValue($value);

        $this->saveSecrets($secrets);
    }

    public function encryptEntireFile(string $filePath)
    {

    }

    private function saveSecrets(array $secrets)
    {
        (new WriteFiles())
            ->setFilePath($this->filePath)
            ->setData(json_encode($secrets))
            ->save();
    }

    public function encryptValue(string $value): string
    {
        $secret = (new SecretKey())->retrieve();
        $algo = SecretsConfig::get('encrypt.algorithm');

        if (!in_array($algo, openssl_get_cipher_methods(true), true)) {
            throw new \Exception('Unknown algorithm [in config.yaml]. For a list of supported algorithms visit: (https://secure.php.net/manual/en/function.openssl-get-cipher-methods.php)');
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($value, $algo, hex2bin($secret), OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function getSecretFilePath(string $app, string $env): string
    {
        $location = SecretsConfig::get('secrets_files.location');
        $prefix = SecretsConfig::get('secrets_files.prefix');

        if (empty($location)) {
            throw new \Exception("Location for saving secrets files is empty from config.yaml [missing secrets_files.location]");
        }

        return rtrim($location, '/') . "/$prefix{$env}_$app.json";
    }

}