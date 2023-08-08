<?php

namespace SecretsManager\Command;

interface CommandInterface
{

    public function __construct(CommandLine $cli);

    public function run(): void;

}