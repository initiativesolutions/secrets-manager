<?php

namespace Tests;

use SecretsManager\Engine\Encrypt;
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

        $encrypt = new Encrypt($app, $env);
        $encrypt->encryptSingleToken($token, $value);

        $tokens = (new SecretsProvider())
            ->decryptByFiles((new KeyVault())->getKeyFilePath(), $encrypt->getFilePath());

        $this->assertEquals(["$token" => $value], $tokens, "Tokens decrypt failed ! check here SecretsProvider::decrypt");
    }

}