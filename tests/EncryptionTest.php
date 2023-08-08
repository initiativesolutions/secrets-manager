<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SecretsManager\Guard\Encrypt;

class EncryptionTest extends TestCase
{

    public function testEncryptSingleToken()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $this->assertSecretsFile($encrypt, [$token => $value]);
    }

    public function testEncryptJsonFile()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["GITHUB_TOKEN" => "123456", "NPM_TOKEN" => "789456"];
        $filePath = __DIR__ . '/file-for-test.json';

        file_put_contents($filePath, json_encode($tokens));

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptJsonFile($filePath);

        $this->assertSecretsFile($encrypt, $tokens);
    }

    public function testTwoTimesEncrypt()
    {
        $app = "secrets-app";
        $env = "test";
        $firstToken = "GITHUB_TOKEN";
        $firstValue = "123456";
        $secondToken = "GITHUB_TOKEN";
        $secondValue = "123456";

        $encrypt = new Encrypt($app, $env);
        // first time
        $encrypt->encryptSingleToken($firstToken, $firstValue);

        $this->assertSecretsFile($encrypt, [$firstToken => $firstValue], false);

        // second time
        $encrypt->encryptSingleToken($secondToken, $secondValue);

        $this->assertSecretsFile($encrypt, [$firstToken => $firstValue, $secondToken => $secondValue]);
    }

    private function assertSecretsFile(Encrypt $encrypt, array $tokens, bool $deleteFile = true)
    {
        $filePath = $encrypt->getFilePath();

        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);

        $content = file_get_contents($filePath);

        $this->assertJson($content);

        $json = json_decode($content, true);

        foreach ($tokens as $token => $value) {
            $this->assertArrayHasKey($token, $json);
            $this->assertNotEmpty($json[$token]);
        }

        if ($deleteFile) {
            unlink($filePath); // remove file for test
        }
    }

}