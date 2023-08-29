<?php

namespace SecretsManager;

use SecretsManager\Exception\ConfigKeyMissingException;
use Symfony\Component\Yaml\Yaml;

class SecretsConfig
{

    public static string $configPath;

    public static function get(string $key): ?string
    {
        $config = Yaml::parseFile(self::getConfigPath());
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

    public static function getConfigPath(): string
    {
        if (!empty(self::$configPath)) {
            return self::$configPath;
        }

        return $_SERVER['HOME'] . '/.config/secretsmanager.yaml';
    }

}