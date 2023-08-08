<?php

namespace SecretsTests;

use PHPUnit\Framework\TestCase;
use SecretsManager\Actions\SecretsEncryption;
use SecretsTests\Mocked\SecretsCommandLineMocked;

class SecretsEncryptionTest extends TestCase
{

    public function testEncryptSingleToken()
    {
        $token = "GITHUB_TOKEN";
        $value = "123456";
        $app = "secrets-app";
        $env = "test";
        $args = ["bin/secretctl", "encrypt", $token, "-app=$app", "-env=$env"];

        $cli = (new SecretsCommandLineMocked($args))
            ->setMockedRead($value);

        $encrypt = new SecretsEncryption($cli);
        $encrypt->run();
        $filePath = $encrypt->getSecretFilePath($app, $env);

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);

        $content = file_get_contents($filePath);

        $this->assertJson($content);

        $json = json_decode($content, true);

        $this->assertArrayHasKey("GITHUB_TOKEN", $json);
        $this->assertNotEmpty($json["GITHUB_TOKEN"]);

        unlink($filePath); // remove file for test
    }

}