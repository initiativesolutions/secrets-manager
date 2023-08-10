<?php

namespace SecretsManager\FileAccess;

use SecretsManager\Exception\NoFilePermissionException;

class ReadFiles implements FileAccessInterface
{

    private string $mode = 'r';
    private string $filePath;

    public function setFilePath(string $filePath): ReadFiles
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setAccessMode(string $mode): ReadFiles
    {
        $this->mode = $mode;
        return $this;
    }

    public function read(): string
    {
        if (!$this->filePath || !$this->fileExist()) {
            throw new NoFilePermissionException("You can't read, filePath wrong or file not exist [$this->filePath]");
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

    /**
     * @param string $directory path to directory
     * @return ReadFiles[] array of each file with data
     */
    public static function getReadableFiles(string $directory): array
    {
        $data = [];

        if (is_dir($directory)) {
            $data = array_map(
                fn($file) => (new self())->setFilePath(rtrim($directory, '/') . '/' . $file),
                array_diff(scandir($directory), ['.', '..'])
            );
        }

        return $data;
    }

}