<?php

namespace Tests;

use SecretsManager\SecretsEngine\Rotate;
use SecretsManager\Exception\NoSecurityKeyException;
use SecretsManager\SecretsEngine\SecretsEngine;
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

        $engine = new SecretsEngine($app, $env);
        $engine->encryptSingleToken($token, $value);

        $firstSecretKey = (new KeyVault())->retrieve();
        $firstTokenValue = array_values($engine->getTokens())[0];

        $this->assertNotEmpty($firstSecretKey);
        $this->assertNotEmpty($firstTokenValue);

        (new Rotate())->rotate();

        $secondSecretKey = (new KeyVault())->retrieve();
        $secondTokens = $engine->getTokens();
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