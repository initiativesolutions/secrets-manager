<?php

namespace Tests;

use SecretsManager\SecurityKey\KeyVault;

class KeyGeneratorTest extends SecretsTestCase
{

    public function testGenerate()
    {
        $keygen = new KeyVault();
        $filePath = $keygen->getKeyFilePath();

        $keygen->generate();

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);
    }

    public function testRetrieve()
    {
        $keygen = new KeyVault();
        $keygen->generate();
        $key = $keygen->retrieve();

        $this->assertGreaterThan(0, strlen($key), "Secret key is empty, check [KeyGenerator::retrieve]");
    }

}