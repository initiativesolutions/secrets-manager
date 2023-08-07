<?php

namespace SecretsTests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use SecretsManager\SecretsCommandLine;
use SecretsTests\Providers\SecretsCommandLineProvider;

class SecretsCommandLineTest extends TestCase
{

    #[DataProviderExternal(SecretsCommandLineProvider::class, 'parseArgsAndOptsProvider')]
    public function testParseArgsAndOpts(array $argv, array $expectedArgs, array $expectedOpts)
    {
        $cli = new SecretsCommandLine($argv);

        $this->assertEquals($expectedArgs, $cli->getArgs(), 'Parsing arguments failed => check SecretsCommandLine::parseArgsAndOpts');
        $this->assertEquals($expectedOpts, $cli->getOpts(), 'Parsing options failed => check SecretsCommandLine::parseArgsAndOpts');
    }

    #[DataProviderExternal(SecretsCommandLineProvider::class, 'getActionProvider')]
    public function testGetAction(array $argv, ?string $expectedAction)
    {
        $cli = new SecretsCommandLine($argv);

        $this->assertEquals($expectedAction, $cli->getAction(), 'Bad CLI action => check SecretsCommandLine::getAction');
    }
}