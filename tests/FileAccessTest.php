<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SecretsManager\FileAccess\ReadFiles;
use SecretsManager\FileAccess\WriteFiles;

class FileAccessTest extends TestCase
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

        $data = (new ReadFiles())
            ->setFilePath($filePath)
            ->read();

        $this->assertEquals($random, $data);

        unlink($filePath); // delete file after test
    }

    public function testReadEmptyFile()
    {
        $filePath = __DIR__ . '/file-for-test.txt';

        $this->createAndAssertFile("", $filePath);

        $data = (new ReadFiles())
            ->setFilePath($filePath)
            ->read();

        $this->assertEquals("", $data);

        unlink($filePath); // delete file after test
    }

    public function testReadJson()
    {
        $jsonData = ['key1' => 'value1'];
        $filePath = __DIR__ . '/file-for-test.json';

        $this->createAndAssertFile(json_encode($jsonData), $filePath);

        $data = (new ReadFiles())
            ->setFilePath($filePath)
            ->readJson();

        $this->assertEquals($jsonData, $data);

        unlink($filePath); // delete file after test
    }

    private function createAndAssertFile($data, $filePath)
    {
        (new WriteFiles())
            ->setData($data)
            ->setFilePath($filePath)
            ->save();

        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, $data);
    }

}