<?php

namespace SecretsManager\Actions;

use SecretsManager\SecretsCommandLine;

interface SecretsActionInterface
{

    public function __construct(SecretsCommandLine $cli);

    public function run(): void;

}