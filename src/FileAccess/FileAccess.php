<?php

namespace SecretsManager\FileAccess;

use SecretsManager\Exception\NoFilePermissionException;

class FileAccess
{

    private string $filePath;
    private string $data = '';

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function setData(string $data): FileAccess
    {
        $this->data = $data;
        return $this;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
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

        $stream = fopen($this->filePath, 'w');

        if (!$stream) {
            throw new NoFilePermissionException("You don't have the permission to write to this file [$this->filePath]");
        }

        fwrite($stream, $this->data);
        fclose($stream);
    }

    public function delete(): void
    {
        unlink($this->filePath);
    }

    public function read(): string
    {
        if (!$this->filePath || !$this->fileExist()) {
            throw new NoFilePermissionException("You can't read, filePath wrong or file not exist [$this->filePath]");
        }

        $handle = fopen($this->filePath, 'r');
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

    /**
     * @param string $directory path to directory
     * @return FileAccess[] array of each file with data
     */
    public static function getDirectoryFilesAccess(string $directory): array
    {
        $data = [];

        if (is_dir($directory)) {
            $data = array_map(
                fn($file) => new self(rtrim($directory, '/') . '/' . $file),
                array_diff(scandir($directory), ['.', '..'])
            );
        }

        return $data;
    }

}