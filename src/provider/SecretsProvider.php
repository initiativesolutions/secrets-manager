<?php

namespace SecretsManager\Provider;

use SecretsManager\FileAccess\ReadFiles;

class SecretsProvider
{

    public function decrypt()
    {

        $storage = new ReadFiles();
    }

}