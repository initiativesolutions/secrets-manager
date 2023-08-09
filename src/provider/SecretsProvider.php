<?php

namespace SecretsManager\Provider;

use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\SecretsConfig;

class SecretsProvider
{

    /**
     * @param string $secretKeyPath path encrypted key
     * @param string $secretsTokensPath path to secrets json tokens
     */
    public function decrypt(string $secretKeyPath, string $secretsTokensPath, string $algo = null): array
    {
        if (is_null($algo)) {
            $algo = SecretsConfig::get('encrypt.algorithm');
        }

        $secretKey = (new ReadFiles())
            ->setFilePath($secretKeyPath)
            ->read();

        $secretTokens = (new ReadFiles())
            ->setFilePath($secretsTokensPath)
            ->readJson();

        return array_map(function ($value) use ($secretKey, $algo) {
            $decodedValue = base64_decode($value);
            $iv = substr($decodedValue, 0, 16);
            $encryptedValue = substr($decodedValue, 16);
            return openssl_decrypt($encryptedValue, $algo, hex2bin($secretKey), OPENSSL_RAW_DATA, $iv);
        }, $secretTokens);
    }

}