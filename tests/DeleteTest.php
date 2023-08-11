<?php

namespace Tests;

use SecretsManager\Exception\NoSecretTokenException;
use SecretsManager\FileAccess\FileAccess;
use SecretsManager\SecretsEngine\SecretsEngine;

class DeleteTest extends SecretsTestCase
{

    public function testDelete()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "BITBUCKET";

        $file = $this->createSecrets($app, $env, $token, "123456");

        $engine = new SecretsEngine($app, $env);
        $engine->delete($token);

        $secrets = (new FileAccess($file))
            ->readJson();

        $this->assertArrayNotHasKey($token, $secrets);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NoSecretTokenException::class);

        $app = "secrets-app";
        $env = "test";

        $this->createSecrets($app, $env, "BITBUCKET", "123456");

        $engine = new SecretsEngine($app, $env);
        $engine->delete("GITHUB");

    }

    private function createSecrets(string $app, string $env, string $token, string $value): string
    {
        $engine = (new SecretsEngine($app, $env));
        $engine->encryptSingleToken($token, $value);

        return $engine->getFilePath();
    }

}