<?php

namespace SecretsManager\Guard;

use SecretsManager\FileAccess\DeleteFiles;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\Key\SecretKey;
use SecretsManager\SecretsConfig;

class Encrypt extends Guard
{

    public function encryptSingleToken(string $token, string $value): void
    {
        $secrets = $this->readJsonSecrets();

        $secrets[$token] = $this->encryptValue($value);

        $this->saveSecrets($secrets);
    }

    public function encryptJsonFile(string $filePath, bool $removeFile = false): void
    {
        $tokens = (new ReadFiles())
            ->setFilePath($filePath)
            ->readJson();

        $secrets = array_map(fn ($value) => $this->encryptValue($value), $tokens);

        $this->saveSecrets($secrets);

        if ($removeFile) {
            (new DeleteFiles())
                ->setFilePath($filePath)
                ->delete();
        }
    }

    protected function encryptValue(string $value): string
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

}