<?php

namespace SecretsManager\FileAccess;

use SecretsManager\Exception\NoFilePermissionException;

class WriteFiles implements FileAccessInterface
{

    private string $mode = 'w';
    private string $data = '';
    private string $filePath;

    public function setData(string $data): WriteFiles
    {
        $this->data = $data;
        return $this;
    }

    public function setFilePath(string $filePath): WriteFiles
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setAccessMode(string $mode): WriteFiles
    {
        $this->mode = $mode;
        return $this;
    }


    public function save(): void
    {
        $directory = dirname($this->filePath);

        if (!is_dir($directory)) {
            $success = mkdir($directory, 0777, true);

            if (!$success) {
                throw new NoFilePermissionException("You don't have the permission to use mkdir [$this->filePath]");
            }
        }

        $stream = fopen($this->filePath, $this->mode);

        if (!$stream) {
            throw new NoFilePermissionException("You don't have the permission to write to this file [$this->filePath]");
        }

        fwrite($stream, $this->data);
        fclose($stream);
    }

}