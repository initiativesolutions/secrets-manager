<?php

namespace SecretsManager;

use Symfony\Component\Yaml\Yaml;

class SecretsConfig
{

    public static function get(string $key): ?string
    {
        $config = Yaml::parseFile( __DIR__ . '/../config.yaml');
        $explode = explode('.', $key);

        foreach ($explode as $k) {
            if (isset($config[$k])) {
                $config = $config[$k];
            } else {
                throw new \Exception("key not found in config.yaml [$key]");
            }
        }

        return $config;
    }

}