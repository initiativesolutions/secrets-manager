<?php

namespace Tests;

use SecretsManager\FileAccess\FileAccess;

class FileAccessTest extends SecretsTestCase
{

    public function testSave()
    {
        $this->createAndAssertFile(uniqid(), self::$fakeTextFile);
    }

    public function testRead()
    {
        $random = uniqid();
        $filePath = self::$fakeTextFile;

        $this->createAndAssertFile($random, $filePath);

        $data = (new FileAccess($filePath))
            ->read();

        $this->assertEquals($random, $data);
    }

    public function testReadEmptyFile()
    {
        $filePath = self::$fakeTextFile;

        $this->createAndAssertFile("", $filePath);

        $data = (new FileAccess($filePath))
            ->read();

        $this->assertEquals("", $data);
    }

    public function testReadJson()
    {
        $jsonData = ['key1' => 'value1'];
        $filePath = self::$fakeJsonFile;

        $this->createAndAssertFile(json_encode($jsonData), $filePath);

        $data = (new FileAccess($filePath))
            ->readJson();

        $this->assertEquals($jsonData, $data);
    }

    private function createAndAssertFile($data, $filePath)
    {
        (new FileAccess($filePath))
            ->setData($data)
            ->save();

        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, $data);
    }

}