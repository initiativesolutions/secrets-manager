<?php

namespace Tests;

use SecretsManager\SecretsConfig;

abstract class SecretsTestCase extends \PHPUnit\Framework\TestCase
{

    public static string $configFile = __DIR__ . "/DataProviders/config-test.yaml";
    public static string $fakeJsonFile = __DIR__ . "/DataProviders/json-test.json";
    public static string $fakeTextFile = __DIR__ . "/DataProviders/text-test.txt";

    public static function setUpBeforeClass(): void
    {
        SecretsConfig::$configPath = self::$configFile;
    }

    public static function tearDownAfterClass(): void
    {
        self::removeTestsFiles();
    }

    protected function setUp(): void
    {
        self::removeTestsFiles();
    }

    public static function removeTestsFiles()
    {
        if (file_exists(self::$fakeJsonFile)) {
            unlink(self::$fakeJsonFile);
        }

        if (file_exists(self::$fakeTextFile)) {
            unlink(self::$fakeTextFile);
        }

        self::rmdirRecursive(SecretsConfig::get('secrets_files.location'));
        self::rmdirRecursive(SecretsConfig::get('encryption_key.location'));
    }

    public static function rmdirRecursive($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $contents = array_diff(scandir($directory), ['.', '..']);
        foreach ($contents as $item) {
            $path = "$directory/$item";
            is_dir($path) ? self::rmdirRecursive($path) : unlink($path);
        }

        rmdir($directory);
    }
}