<?php

namespace SecretsManager;

class SecretsStorage
{

    /** @see https://www.php.net/manual/fr/function.fopen.php */
    private string $writeMode = 'w';
    private string $readMode = 'r';
    private string $data = '';
    private string $filePath;

    public function setData(string $data): SecretsStorage
    {
        $this->data = $data;
        return $this;
    }

    public function setFilePath(string $filePath): SecretsStorage
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setWriteMode(string $writeMode): SecretsStorage
    {
        $this->writeMode = $writeMode;
        return $this;
    }

    public function setReadMode(string $readMode): SecretsStorage
    {
        $this->readMode = $readMode;
        return $this;
    }

    public function save(): void
    {
        $directory = dirname($this->filePath);

        if (!is_dir($directory)) {
            $success = mkdir($directory, 0777, true);

            if (!$success) {
                throw new \Exception("You don't have the permission to use mkdir [$this->filePath]");
            }
        }

        $stream = fopen($this->filePath, $this->writeMode);

        if (!$stream) {
            throw new \Exception("You don't have the permission to write to this file [$this->filePath]");
        }

        fwrite($stream, $this->data);
        fclose($stream);
    }

    public function read(): string
    {
        if (!$this->filePath || !$this->fileExist()) {
            throw new \Exception("You can't read, filePath wrong or file not exist [$this->filePath]");
        }

        $handle = fopen($this->filePath, $this->readMode);
        $contents = ($size = filesize($this->filePath)) ? fread($handle, $size) : "";
        fclose($handle);

        return $contents;
    }

    public function readJson(): array
    {
        $content = $this->read();
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function fileExist(): bool
    {
        return file_exists($this->filePath);
    }
}