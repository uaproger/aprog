<?php

namespace Src;

class Logger
{
    const WHITE = "\033[1;37m";
    const RED = "\033[0;31m";
    const GREEN = "\033[0;32m";
    const BLUE = "\033[0;34m";
    const BACKGROUND_ORANGE = "\033[48;2;255;136;0m";   // #FF8800 (Жовто-оранжевий)
    const BACKGROUND_RED = "\033[48;2;255;40;0m";      // #FF2800 (Червоний)
    const BACKGROUND_DARK_BLUE = "\033[48;2;4;52;88m"; // #043458 (Темно-синій)
    const BOLD = "\033[1m";
    const NC = "\033[0m";

    public static array $config = [
        'paths' => [
            'properties' => [
                'namespace' => [
                    'app' => 'App\Properties\Items',
                ],
                'use' => [
                    'app' => 'App\Properties',
                ],
            ]
        ],
    ];

    public static function info($message): string
    {
        return PHP_EOL . sprintf("%s" . self::BACKGROUND_DARK_BLUE . " INFO " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }

    public static function warn($message): string
    {
        return PHP_EOL . sprintf("%s" . self::BACKGROUND_ORANGE . " WARN " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }

    public static function error($message): string
    {
        return PHP_EOL . sprintf("%s" . self::BACKGROUND_RED . " ERROR " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }
}
