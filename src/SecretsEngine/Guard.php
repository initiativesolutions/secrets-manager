<?php

namespace SecretsManager\SecretsEngine;

use SecretsManager\Exception\AlgorithmNotSupportedException;
use SecretsManager\Exception\NoSecurityKeyException;
use SecretsManager\SecurityKey\KeyVault;
use SecretsManager\SecretsConfig;

class Guard
{

    public static function encryptValue(string $value): string
    {
        $secret = (new KeyVault())->retrieve();
        $algo = SecretsConfig::get('encrypt.algorithm');

        if (empty($secret)) {
            throw new NoSecurityKeyException();
        }

        if (!in_array($algo, openssl_get_cipher_methods(true), true)) {
            throw new AlgorithmNotSupportedException();
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($value, $algo, hex2bin($secret), OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    public static function decryptValue(string $securityKey, string $encodedValue, ?string $algo = null): string
    {
        if (is_null($algo)) {
            $algo = SecretsConfig::get('encrypt.algorithm');
        }

        $decodedValue = base64_decode($encodedValue);
        $iv = substr($decodedValue, 0, 16);
        $encryptedValue = substr($decodedValue, 16);
        return openssl_decrypt($encryptedValue, $algo, hex2bin($securityKey), OPENSSL_RAW_DATA, $iv);
    }

}