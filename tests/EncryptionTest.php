<?php

namespace Tests;

use SecretsManager\Engine\Encrypt;

class EncryptionTest extends SecretsTestCase
{

    public function testEncryptSingleToken()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $this->assertSecretsFile($encrypt, [$token]);
    }

    public function testEncryptJsonFile()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["GITHUB_TOKEN" => "123456", "NPM_TOKEN" => "789456"];
        $filePath = self::$fakeJsonFile;

        file_put_contents($filePath, json_encode($tokens));

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptJsonFile($filePath);

        $this->assertSecretsFile($encrypt, array_keys($tokens));
    }

    public function testEncryptJsonFileAndDelete()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["GITHUB_TOKEN" => "123456", "NPM_TOKEN" => "789456"];
        $filePath = self::$fakeJsonFile;

        file_put_contents($filePath, json_encode($tokens));

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptJsonFile($filePath, true);

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

        $encrypt = new Encrypt($app, $env);
        // first time
        $encrypt->encryptSingleToken($firstToken, $firstValue);

        $this->assertSecretsFile($encrypt, [$firstToken]);

        // second time
        $encrypt->encryptSingleToken($secondToken, $secondValue);

        $this->assertSecretsFile($encrypt, [$firstToken, $secondToken]);
    }

    private function assertSecretsFile(Encrypt $encrypt, array $tokens)
    {
        $filePath = $encrypt->getFilePath();

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