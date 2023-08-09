<?php

namespace SecretsManager\Guard;

use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\Key\SecretKey;
use SecretsManager\Provider\SecretsProvider;
use SecretsManager\SecretsConfig;

class Rotate
{

    public function rotate(): array
    {
        $secretKeyManager = new SecretKey();
        $oldSecretKey = $secretKeyManager->retrieve();
        $secretKeyManager->generate();

        $tokensLocation = SecretsConfig::get('secrets_files.location');
        $tokensFiles = ReadFiles::getDirectory($tokensLocation);

        foreach ($tokensFiles as $file) {
            $decryptedTokens = (new SecretsProvider())
                ->decryptWithValues($oldSecretKey, $file->readJson());

            $encryption = (new Encrypt("", ""))
                ->setFilePath($file->getFilePath());

            foreach ($decryptedTokens as $token => $value) {
                $encryption->encryptSingleToken($token, $value);
            }
        }

        return array_map(fn($file) => $file->getFilePath(), $tokensFiles);
    }

}