<?php

namespace Tests;

use SecretsManager\Guard\Encrypt;
use SecretsManager\Key\SecretKey;
use SecretsManager\Provider\SecretsProvider;

class ProviderTest extends SecretsTestCase
{

    public function testDecrypt()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "GITHUB_TOKEN";
        $value = "123456";

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $tokens = (new SecretsProvider())
            ->decrypt((new SecretKey())->getKeyFilePath(), $encrypt->getFilePath());

        $this->assertEquals(["$token" => $value], $tokens, "Tokens decrypt failed ! check here SecretsProvider::decrypt");
    }

}