<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SecretsManager\Key\SecretKey;

class KeyGeneratorTest extends TestCase
{

    public function testGenerate()
    {
        $keygen = new SecretKey();
        $filePath = $keygen->getKeyFilePath();

        $keygen->generate();

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);
    }

    public function testRetrieve()
    {
        $keygen = new SecretKey();
        $keygen->generate();
        $key = $keygen->retrieve();

        $this->assertGreaterThan(0, strlen($key), "Secret key is empty, check [KeyGenerator::retrieve]");
    }

}