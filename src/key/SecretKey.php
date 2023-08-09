<?php

namespace SecretsManager\Key;

use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\FileAccess\WriteFiles;
use SecretsManager\SecretsConfig;

class SecretKey
{

    public function getKeyFilePath(): string
    {
        $location = SecretsConfig::get('encryption_key.location');
        $filename = SecretsConfig::get('encryption_key.file_name');

        if (empty($location) || empty($filename)) {
            throw new \Exception("Location for saving secret key is empty from config.yaml [missing encryption_key.location]");
        }

        return rtrim($location, '/') . '/' . ltrim($filename, '/');
    }

    public function retrieve(): string
    {
        $storage = (new ReadFiles())
            ->setFilePath($this->getKeyFilePath());

        if (!$storage->fileExist()) {
            $this->generate();
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