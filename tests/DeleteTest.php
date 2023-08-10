<?php

namespace Tests;

use SecretsManager\Exception\NoSecretTokenException;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\Engine\Delete;
use SecretsManager\Engine\Encrypt;

class DeleteTest extends SecretsTestCase
{

    public function testDelete()
    {
        $app = "secrets-app";
        $env = "test";
        $token = "BITBUCKET";

        $file = $this->createSecrets($app, $env, $token, "123456");

        $delete = new Delete($app, $env);
        $delete->delete($token);

        $secrets = (new ReadFiles())
            ->setFilePath($file)
            ->readJson();

        $this->assertArrayNotHasKey($token, $secrets);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NoSecretTokenException::class);

        $app = "secrets-app";
        $env = "test";

        $this->createSecrets($app, $env, "BITBUCKET", "123456");

        $delete = new Delete($app, $env);
        $delete->delete("GITHUB");

    }

    private function createSecrets(string $app, string $env, string $token, string $value): string
    {
        $encrypt = (new Encrypt($app, $env));
        $encrypt->encryptSingleToken($token, $value);

        return $encrypt->getFilePath();
    }

}