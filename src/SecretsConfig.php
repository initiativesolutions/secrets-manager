<?php

namespace SecretsManager;

use SecretsManager\Exception\ConfigKeyMissingException;
use Symfony\Component\Yaml\Yaml;

class SecretsConfig
{

    public static string $configPath = __DIR__ . '/../config.yaml';

    public static function get(string $key): ?string
    {
        $config = Yaml::parseFile(self::$configPath);
        $explode = explode('.', $key);

        foreach ($explode as $k) {
            if (isset($config[$k])) {
                $config = $config[$k];
            } else {
                throw new ConfigKeyMissingException($key);
            }
        }

        return $config;
    }

}