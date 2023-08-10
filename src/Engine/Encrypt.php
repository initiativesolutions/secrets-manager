<?php

namespace SecretsManager\Engine;

use SecretsManager\Exception\AlgorithmNotSupportedException;
use SecretsManager\Exception\NoSecurityKeyException;
use SecretsManager\FileAccess\DeleteFiles;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\SecurityKey\KeyVault;
use SecretsManager\SecretsConfig;

class Encrypt extends Engine
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
        $secret = (new KeyVault())->retrieve();
        $algo = SecretsConfig::get('encrypt.algorithm');

        if (empty($secret)) {
            throw new NoSecurityKeyException();
        }

        if (!$this->algorithmSupported($algo)) {
            throw new AlgorithmNotSupportedException();
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($value, $algo, hex2bin($secret), OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    private function algorithmSupported(string $algo): bool
    {
        return in_array($algo, openssl_get_cipher_methods(true), true);
    }

}