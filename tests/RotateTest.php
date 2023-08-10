<?php

namespace Tests;

use SecretsManager\Engine\Encrypt;
use SecretsManager\Engine\Retrieve;
use SecretsManager\Engine\Rotate;
use SecretsManager\Exception\NoSecurityKeyException;
use SecretsManager\SecurityKey\KeyVault;
use SecretsManager\Provider\SecretsProvider;

class RotateTest extends SecretsTestCase
{

    public function testRotate()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $firstSecretKey = (new KeyVault())->retrieve();
        $firstTokenValue = array_values((new Retrieve($app, $env))->getTokens())[0];

        $this->assertNotEmpty($firstSecretKey);
        $this->assertNotEmpty($firstTokenValue);

        (new Rotate())->rotate();

        $secondSecretKey = (new KeyVault())->retrieve();
        $secondTokens = (new Retrieve($app, $env))->getTokens();
        $secondTokenValue = array_values($secondTokens)[0];

        $this->assertNotEmpty($secondSecretKey);
        $this->assertNotEmpty($secondTokenValue);
        $this->assertNotSame($firstSecretKey, $secondSecretKey);
        $this->assertNotSame($firstTokenValue, $secondTokenValue);

        $decryptedTokens = (new SecretsProvider())
            ->decryptByTokens($secondSecretKey, $secondTokens);

        $this->assertSame([$token => $value], $decryptedTokens);
    }

    public function testRotateException()
    {
        $this->expectException(NoSecurityKeyException::class);

        (new Rotate())->rotate();
    }

}