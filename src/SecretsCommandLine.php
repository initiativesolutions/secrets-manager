<?php

namespace SecretsManager;

class SecretsCommandLine
{
    private array $argv;
    private array $args = [];
    private array $opts = [];

    public function __construct(array $argv)
    {
        $this->argv = $argv;
        $this->parseArgsAndOpts();
    }

    public function write(string $data): void
    {
        fwrite(STDOUT, $data . "\n");
    }

    public function getAction(): ?string
    {
        return $this->argv[1] ?? null;
    }

    public function success(string $data): void
    {
        $this->write("\033[32;40m$data\033[0m");
    }

    public function error(string $data): void
    {
        $this->write("\033[31;40m$data\033[0m");
    }

    public function info(string $data): void
    {
        $this->write("\033[34;1m$data\033[0m");
    }

    private function parseArgsAndOpts(): void
    {
        $prevOptKey = null;

        for ($i = 2, $len = count($this->argv); $i < $len; $i++) {
            $value = $this->argv[$i];
            $isOptKey = stripos($value, '-') === 0;

            if ($prevOptKey) {
                $this->opts[$prevOptKey] = $isOptKey ? true : $value;
                $prevOptKey = $isOptKey ? ltrim($value, '-') : null;
            } elseif (!$isOptKey) {
                $this->args[] = $value;
            } else {
                $prevOptKey = ltrim($value, '-');
                if ($i + 1 >= $len && !str_contains($value, '=')) {
                    $this->opts[$prevOptKey] = true;
                }
            }

            if ($isOptKey && str_contains($value, '=')) {
                list($key, $val) = explode('=', ltrim($value, '-'), 2);
                $this->opts[$key] = $val;
                $prevOptKey = null;
            }
        }
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getOpts(): array
    {
        return $this->opts;
    }

    public function read(?string $promptMessage): string
    {
        return readline($promptMessage);
    }

}