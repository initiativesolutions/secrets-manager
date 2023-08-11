<?php

namespace Tests;

use SecretsManager\SecretsEngine\Guard;
use SecretsManager\SecretsEngine\SecretsEngine;
use SecretsManager\SecurityKey\KeyVault;
use SecretsManager\Provider\SecretsProvider;

class ProviderTest extends SecretsTestCase
{

    public function testDecrypt()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $engine = new SecretsEngine($app, $env);
        $engine->encryptSingleToken($token, $value);

        $tokens = (new SecretsProvider())
            ->decryptByFiles((new KeyVault())->getKeyFilePath(), $engine->getFilePath());

        $this->assertEquals(["$token" => $value], $tokens, "Tokens decrypt failed ! check here SecretsProvider::decrypt");
    }

}