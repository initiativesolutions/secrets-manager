<?php

namespace SecretsManager\SecretsEngine;

use SecretsManager\Exception\NoSecurityKeyException;
use SecretsManager\FileAccess\FileAccess;
use SecretsManager\SecurityKey\KeyVault;
use SecretsManager\Provider\SecretsProvider;
use SecretsManager\SecretsConfig;

class Rotate
{

    public function rotate(): array
    {
        $secretKeyManager = new KeyVault();
        $oldSecretKey = $secretKeyManager->retrieve(false);

        if (empty($oldSecretKey)) {
            throw new NoSecurityKeyException("Rotation failed!");
        }

        $secretKeyManager->generate();

        $tokensLocation = SecretsConfig::get('secrets_files.location');
        $tokensFiles = FileAccess::getDirectoryFilesAccess($tokensLocation);

        foreach ($tokensFiles as $file) {
            $decryptedTokens = (new SecretsProvider())
                ->decryptByTokens($oldSecretKey, $file->readJson());

            $encryption = (new SecretsEngine())
                ->setFilePath($file->getFilePath());

            foreach ($decryptedTokens as $token => $value) {
                $encryption->encryptSingleToken($token, $value);
            }
        }

        return array_map(fn($file) => $file->getFilePath(), $tokensFiles);
    }

}