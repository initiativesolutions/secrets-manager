<?php

namespace Tests;

use SecretsManager\Guard\Encrypt;
use SecretsManager\Guard\Retrieve;
use SecretsManager\Guard\Rotate;
use SecretsManager\Key\SecretKey;
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

        $firstSecretKey = (new SecretKey())->retrieve();
        $firstTokenValue = array_values((new Retrieve($app, $env))->getTokens())[0];

        $this->assertNotEmpty($firstSecretKey);
        $this->assertNotEmpty($firstTokenValue);

        (new Rotate())->rotate();

        $secondSecretKey = (new SecretKey())->retrieve();
        $secondTokens = (new Retrieve($app, $env))->getTokens();
        $secondTokenValue = array_values($secondTokens)[0];

        $this->assertNotEmpty($secondSecretKey);
        $this->assertNotEmpty($secondTokenValue);
        $this->assertNotSame($firstSecretKey, $secondSecretKey);
        $this->assertNotSame($firstTokenValue, $secondTokenValue);

        $decryptedTokens = (new SecretsProvider())
            ->decryptWithValues($secondSecretKey, $secondTokens);

        $this->assertSame([$token => $value], $decryptedTokens);
    }

}