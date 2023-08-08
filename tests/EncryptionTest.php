<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SecretsManager\Guard\Encrypt;

class EncryptionTest extends TestCase
{

    public function testEncryptSingleToken()
    {
        $token = "GITHUB_TOKEN";
        $value = "123456";
        $app = "secrets-app";
        $env = "test";

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $filePath = $encrypt->getFilePath();

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