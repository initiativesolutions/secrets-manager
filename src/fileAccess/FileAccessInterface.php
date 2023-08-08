<?php

namespace SecretsManager\FileAccess;

interface FileAccessInterface
{

    public function setFilePath(string $filePath): FileAccessInterface;

    /** @see https://www.php.net/manual/fr/function.fopen.php */
    public function setAccessMode(string $mode): FileAccessInterface;

}