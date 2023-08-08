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

        if (!empty($opts['file'])) {
            $data = $this->encryptEntireFile($opts['file']);
        } else {
            $data = $this->encryptSingleToken(array_shift($args), $storage);
        }

        $storage->setData(json_encode($data))
            ->save();

        $this->cli->info("Encrypt work [location = $filePath]");
    }

    private function encryptSingleToken(string $token, SecretsStorage $storage): array
    {
        $secrets = [];

        if ($storage->fileExist()) {
            $secrets = $storage->readJson();
        }

        $value = $this->cli->read("Set value for [$token] : ");

        $secrets[$token] = $this->encryptValue($value);

        return $secrets;
    }

    private function encryptEntireFile(string $filePath): array
    {
        return [];
    }

    public function encryptValue(string $value): string
    {
        $secret = (new SecretsKeyGenerator($this->cli))->getSecretKey();
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