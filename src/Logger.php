<?php

namespace Src;

class Logger
{
    const string RED = "\033[0;31m";
    const string GREEN = "\033[0;32m";
    const string YELLOW = "\033[0;33m";
    const string BLUE = "\033[0;34m";
    const string BOLD = "\033[1m";
    const string NC = "\033[0m";

    public static array $config = [
        'paths' => [
            'properties' => [
                'namespace' => [
                    'base' => 'Properties\Files',
                    'app' => 'App\Properties\Items',
                ],
                'use' => [
                    'base' => 'Properties',
                    'app' => 'App\Properties',
                ],
            ]
        ],
    ];

    public static function info($message): string
    {
        return sprintf('%s<info> INFO </info> %s.', '  ', $message);
    }

    public static function warn($message): string
    {
        return sprintf('%s<warn> WARN </warn> %s.', '  ', $message);
    }

    public static function error($message): string
    {
        return sprintf('%s<error> ERROR </error> %s.', '  ', $message);
    }
}
