<?php

namespace SecretsTests\Providers;

class SecretsCommandLineProvider
{

    public static function parseArgsAndOptsProvider(): array
    {
        return [
            [ // Test case 1
                ['secretctl', 'encrypt', 'arg1', '--opt1', 'val1', '--opt2', 'val2'], // argv inputs
                ['arg1'], // expected args
                ['opt1' => 'val1', 'opt2' => 'val2'], // expected opts
            ],
            [ // Test case 2
                ['secretctl', 'encrypt', '--opt1', 'val1', '--opt2', 'val2', '-opt3'], // argv inputs
                [], // expected args
                ['opt1' => 'val1', 'opt2' => 'val2', 'opt3' => true], // expected opts
            ],
            [ // Test case 3
                ['secretctl', 'encrypt', 'arg1', 'arg2'], // argv inputs
                ['arg1', 'arg2'], // expected args
                [], // expected opts
            ],
            [ // Test case 4
                ['secretctl', 'encrypt', 'arg1', '--opt1', '--opt2', '--opt3', 'val3'], // argv inputs
                ['arg1'], // expected args
                ['opt1' => 'val1', 'opt2' => true, 'opt3' => 'val3'], // expected opts
            ],
            [ // Test case 5
                ['secretctl', 'encrypt', '--opt1', 'val1', '--opt2', 'val2', 'arg1'], // argv inputs
                ['arg1'], // expected args
                ['opt1' => 'val1', 'opt2' => 'val2'], // expected opts
            ],
            [ // Test case 6
                ['secretctl', 'encrypt'], // argv inputs
                [], // expected args
                [], // expected opts
            ],
            [ // Test case 7
                ['secretctl', 'encrypt', 'some arg with space', '--opt1', 'some opt with space'], // argv inputs
                ['some arg with space'], // expected args
                ['opt1' => 'some opt with space'], // expected opts
            ],
            [ // Test case 8
                ['secretctl', 'encrypt', '--opt1=val1', 'arg1'], // argv inputs
                ['arg1'], // expected args
                ['opt1' => 'val1'], // expected opts
            ],
            [ // Test case 9
                ['secretctl', 'encrypt', '--opt1', '--opt2=val2', 'arg1'], // argv inputs
                ['arg1'], // expected args
                ['opt1' => true, 'opt2' => 'val2'], // expected opts
            ],
            [ // Test case 10
                ['secretctl', 'encrypt', '--opt1=val1', '--opt2=val2'], // argv inputs
                [], // expected args
                ['opt1' => 'val1', 'opt2' => 'val2'], // expected opts
            ],
        ];
    }

    public static function getActionProvider(): array
    {
        return [
            [['secretctl', 'encrypt'], 'encrypt'],
            [['secretctl', 'encrypt', 'arg1', '--opt1', 'val1'], 'encrypt'],
            [['secretctl'], null],
        ];
    }

}