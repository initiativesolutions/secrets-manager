<?php

namespace SecretsTests\Mocked;


use SecretsManager\SecretsCommandLine;

class SecretsCommandLineMocked extends SecretsCommandLine
{
    private string $mockedRead = "";

    public function read(?string $promptMessage): string
    {
        return $this->mockedRead;
    }

    public function setMockedRead(string $mockedRead): SecretsCommandLineMocked
    {
        $this->mockedRead = $mockedRead;
        return $this;
    }
}