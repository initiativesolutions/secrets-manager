<?php

namespace Tests;

use SecretsManager\Guard\Encrypt;
use SecretsManager\Guard\Retrieve;

class RetrieveTest extends SecretsTestCase
{

    public function testGetTokens()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["BITBUCKET" => "123456", "NPM" => "456789", "INSEE" => "986456"];

        $encrypt = new Encrypt($app, $env);

        foreach ($tokens as $token => $value) {
            $encrypt->encryptSingleToken($token, $value);
        }

        $retrieve = new Retrieve($app, $env);
        $data = $retrieve->getTokens();

        foreach ($tokens as $token => $value) {
            $this->assertArrayHasKey($token, $data);
        }
    }

}