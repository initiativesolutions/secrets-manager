<?php

namespace SecretsTests;

use PHPUnit\Framework\TestCase;
use SecretsManager\SecretsStorage;

class SecretsStorageTest extends TestCase
{

    public function testSave()
    {
        $random = uniqid();
        $filePath = __DIR__ . '/file-for-test.txt';

        $this->createAndAssertFile($random, $filePath);

        unlink($filePath); // delete file after test
    }

    public function testRead()
    {
        $random = uniqid();
        $filePath = __DIR__ . '/file-for-test.txt';

        $this->createAndAssertFile($random, $filePath);

        $data = (new SecretsStorage())
            ->setFilePath($filePath)
            ->read();

        $this->assertEquals($random, $data);

        unlink($filePath); // delete file after test
    }

    public function testReadJson()
    {
        $jsonData = ['key1' => 'value1'];
        $filePath = __DIR__ . '/file-for-test.json';

        $this->createAndAssertFile(json_encode($jsonData), $filePath);

        $data = (new SecretsStorage())
            ->setFilePath($filePath)
            ->readJson();

        $this->assertEquals($jsonData, $data);

        unlink($filePath); // delete file after test
    }

    private function createAndAssertFile($data, $filePath)
    {
        (new SecretsStorage())
            ->setData($data)
            ->setFilePath($filePath)
            ->setWriteMode('w')
            ->save();

        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, $data);
    }

}