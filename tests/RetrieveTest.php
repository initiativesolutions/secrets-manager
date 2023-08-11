<?php

namespace Tests;


use SecretsManager\SecretsEngine\SecretsEngine;

class RetrieveTest extends SecretsTestCase
{

    public function testGetTokens()
    {
        $app = "secrets-app";
        $env = "test";
        $tokens = ["BITBUCKET" => "123456", "NPM" => "456789", "INSEE" => "986456"];

        $engine = new SecretsEngine($app, $env);

        foreach ($tokens as $token => $value) {
            $engine->encryptSingleToken($token, $value);
        }

        $data = $engine->getTokens();

        foreach ($tokens as $token => $value) {
            $this->assertArrayHasKey($token, $data);
        }
    }

}