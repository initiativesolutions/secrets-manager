<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use SecretsManager\Command\CommandLine;
use Tests\DataProviders\CommandLineProvider;

class CommandLineTest extends TestCase
{

    #[DataProviderExternal(CommandLineProvider::class, 'parseArgsAndOptsProvider')]
    public function testParseArgsAndOpts(array $argv, array $expectedArgs, array $expectedOpts)
    {
        $cli = new CommandLine($argv);

        $this->assertEquals($expectedArgs, $cli->getArgs(), 'Parsing arguments failed => check CommandLine::parseArgsAndOpts');
        $this->assertEquals($expectedOpts, $cli->getOpts(), 'Parsing options failed => check CommandLine::parseArgsAndOpts');
    }

    #[DataProviderExternal(CommandLineProvider::class, 'getActionProvider')]
    public function testGetAction(array $argv, ?string $expectedAction)
    {
        $cli = new CommandLine($argv);

        $this->assertEquals($expectedAction, $cli->getAction(), 'Bad CLI action => check CommandLine::getAction');
    }
}