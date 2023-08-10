<?php

namespace SecretsManager\Engine;

use SecretsManager\FileAccess\ReadFiles;

class Retrieve extends Engine
{

    public function getTokens(): array
    {
        return (new ReadFiles())
            ->setFilePath($this->getFilePath())
            ->readJson();
    }

}