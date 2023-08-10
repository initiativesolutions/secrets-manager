<?php

namespace SecretsManager\Command;

use SecretsManager\Guard\Encrypt;
use SecretsManager\Key\SecretKey;

class Encryption implements CommandInterface
{

    private CommandLine $cli;

    public function __construct(CommandLine $cli)
    {
        $this->cli = $cli;
    }

    public function run(): void
    {
        $args = $this->cli->getArgs();
        $opts = $this->cli->getOpts();

        if (!array_key_exists('app', $opts)) {
            throw new \Exception('[app] option is missing !');
        }

        if (!array_key_exists('env', $opts)) {
            throw new \Exception('[env] option is missing !');
        }

        if (empty($args) && empty($opts['file'])) {
            throw new \Exception('Token name (as an argument) or file (as --file option) not found !');
        }

        $encrypt = new Encrypt($opts['app'], $opts['env']);

        if (!empty($opts['file'])) {
            $encrypt->encryptJsonFile($opts['file'], isset($opts['remove-file']));
        } else {
            $token = array_shift($args);
            $value = $this->cli->read("Set value for [$token] : ");
            $encrypt->encryptSingleToken($token, $value);
        }

        $this->cli->success("Success ! Secrets saved here [{$encrypt->getFilePath()}]");

        $secretKeyPath = (new SecretKey())->getKeyFilePath();

        $this->cli->info("With secret key [$secretKeyPath]");
    }

}