<?php

namespace SecretsManager\SecurityKey;

use SecretsManager\Exception\ConfigKeyMissingException;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\FileAccess\WriteFiles;
use SecretsManager\SecretsConfig;

class KeyVault
{

    public function getKeyFilePath(): string
    {
        $location = SecretsConfig::get('encryption_key.location');
        $filename = SecretsConfig::get('encryption_key.file_name');

        if (empty($location)) {
            throw new ConfigKeyMissingException("encryption_key.location");
        }

        if (empty($filename)) {
            throw new ConfigKeyMissingException("encryption_key.file_name");
        }

        return rtrim($location, '/') . '/' . ltrim($filename, '/');
    }

    public function retrieve($generateIfEmpty = true): string
    {
        $storage = (new ReadFiles())
            ->setFilePath($this->getKeyFilePath());

        if (!$storage->fileExist()) {
            if ($generateIfEmpty) {
                $this->generate();
            } else {
                return "";
            }
        }

        return $storage->read();
    }

    public function generate(): string
    {
        $filePath = $this->getKeyFilePath();
        $encryptionKey = bin2hex(random_bytes(32));

        (new WriteFiles())
            ->setData($encryptionKey)
            ->setFilePath($filePath)
            ->save();
        
        return $filePath;
    }

}