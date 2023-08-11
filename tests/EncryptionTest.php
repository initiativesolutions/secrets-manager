<?php

namespace Tests;

use SecretsManager\SecretsEngine\SecretsEngine;

class EncryptionTest extends SecretsTestCase
{

    public function testEncryptSingleToken()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $engine = new SecretsEngine($app, $env);
        $engine->encryptSingleToken($token, $value);

        $this->assertSecretsFile($engine, [$token]);
    }

    public function testEncryptJsonFile()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["GITHUB_TOKEN" => "123456", "NPM_TOKEN" => "789456"];
        $filePath = self::$fakeJsonFile;

        file_put_contents($filePath, json_encode($tokens));

        $engine = new SecretsEngine($app, $env);
        $engine->encryptJsonFile($filePath);

        $this->assertSecretsFile($engine, array_keys($tokens));
    }

    public function testEncryptJsonFileAndDelete()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["GITHUB_TOKEN" => "123456", "NPM_TOKEN" => "789456"];
        $filePath = self::$fakeJsonFile;

        file_put_contents($filePath, json_encode($tokens));

        $engine = new SecretsEngine($app, $env);
        $engine->encryptJsonFile($filePath, true);

        $this->assertFileDoesNotExist($filePath);
    }

    public function testTwoTimesEncrypt()
    {
        $app = "secrets-app";
        $env = "test";
        $firstToken = "GITHUB_TOKEN";
        $firstValue = "123456";
        $secondToken = "NPM_TOKEN";
        $secondValue = "PDKJ-5955-UDHJU";

        $engine = new SecretsEngine($app, $env);
        // first time
        $engine->encryptSingleToken($firstToken, $firstValue);

        $this->assertSecretsFile($engine, [$firstToken]);

        // second time
        $engine->encryptSingleToken($secondToken, $secondValue);

        $this->assertSecretsFile($engine, [$firstToken, $secondToken]);
    }

    private function assertSecretsFile(SecretsEngine $engine, array $tokens)
    {
        $filePath = $engine->getFilePath();

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);

        $content = file_get_contents($filePath);

        $this->assertJson($content);

        $json = json_decode($content, true);

        foreach ($tokens as $token) {
            $this->assertArrayHasKey($token, $json);
            $this->assertNotEmpty($json[$token]);
        }
    }

}