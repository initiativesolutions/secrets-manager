<?php

namespace SecretsTests;

use PHPUnit\Framework\TestCase;
use SecretsManager\Actions\SecretsKeyGenerator;
use SecretsManager\SecretsCommandLine;

class SecretsKeyGeneratorTest extends TestCase
{

    public function testRun()
    {
        $keygen = new SecretsKeyGenerator(new SecretsCommandLine([]));
        $filePath = $keygen->getKeyFilePath();

        $keygen->run();

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);
    }

    public function testGetSecretKey()
    {
        $keygen = new SecretsKeyGenerator(new SecretsCommandLine([]));
        $keygen->run();

        $this->assertGreaterThan(0, strlen($keygen->getSecretKey()), "Secret key is empty, check [SecretsKeyGenerator::getSecretKey]");
    }

}