<?php

namespace SecretsManager\FileAccess;

class ReadFiles implements FileAccessInterface
{

    private string $mode = 'r';
    private string $filePath;

    public function setFilePath(string $filePath): ReadFiles
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setAccessMode(string $mode): ReadFiles
    {
        $this->mode = $mode;
        return $this;
    }

    public function read(): string
    {
        if (!$this->filePath || !$this->fileExist()) {
            throw new \Exception("You can't read, filePath wrong or file not exist [$this->filePath]");
        }

        $handle = fopen($this->filePath, $this->mode);
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