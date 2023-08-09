<?php

namespace SecretsManager\Provider;

use SecretsManager\FileAccess\ReadFiles;

class SecretsProvider
{

    /**
     * @param string $secretKeyPath path encrypted key
     * @param string $secretsTokensPath path to secrets json tokens
     */
    public function decrypt(string $secretKeyPath, string $secretsTokensPath): array
    {
        $secretKey = (new ReadFiles())
            ->setFilePath($secretKeyPath)
            ->read();

        $secretTokens = (new ReadFiles())
            ->setFilePath($secretsTokensPath)
            ->readJson();

        // todo change, just for test

        return [$secretKey, $secretTokens];
    }

}