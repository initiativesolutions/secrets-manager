<?php

namespace SecretsManager\Guard;

use SecretsManager\FileAccess\ReadFiles;

class Retrieve extends Guard
{

    public function getTokens(): array
    {
        return (new ReadFiles())
            ->setFilePath($this->getFilePath())
            ->readJson();
    }

}