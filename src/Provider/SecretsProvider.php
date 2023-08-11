<?php

namespace SecretsManager\Provider;

use SecretsManager\FileAccess\FileAccess;
use SecretsManager\SecretsEngine\Guard;

class SecretsProvider
{

    /**
     * @param string $secretKeyPath path encrypted key
     * @param string $secretsTokensPath path to secrets json tokens
     */
    public function decryptByFiles(string $secretKeyPath, string $secretsTokensPath, string $algo = null): array
    {
        $secretKey = (new FileAccess($secretKeyPath))
            ->read();

        $secretTokens = (new FileAccess($secretsTokensPath))
            ->readJson();

        return $this->decryptByTokens($secretKey, $secretTokens, $algo);
    }

    public function decryptByTokens(string $secretKey, array $secretsTokens, string $algo = null): array
    {
        return array_map(function ($value) use ($secretKey, $secretsTokens, $algo) {
            return Guard::decryptValue($secretKey, $value, $algo);
        }, $secretsTokens);
    }

}