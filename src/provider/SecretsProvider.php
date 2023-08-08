<?php

namespace SecretsManager\Provider;

use SecretsManager\FileAccess\Storage;

class SecretsProvider
{

    public function decrypt()
    {
        $storage = new Storage();
    }

}