<?php

namespace SecretsManager\FileAccess;

class DeleteFiles implements FileAccessInterface
{

    private string $filePath;

    public function setFilePath(string $filePath): DeleteFiles
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setAccessMode(string $mode): DeleteFiles
    {
        return $this;
    }

    public function delete()
    {
        unlink($this->filePath);
    }

}