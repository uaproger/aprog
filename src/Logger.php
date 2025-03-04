<?php

namespace Src;

class Logger
{
    const string RED = "\033[0;31m";
    const string GREEN = "\033[0;32m";
    const string YELLOW = "\033[0;33m";
    const string BLUE = "\033[0;34m";
    const string BACKGROUND_ORANGE = "\033[48;2;255;136;0m";   // #FF8800 (Жовто-оранжевий)
    const string BACKGROUND_RED = "\033[48;2;255;40;0m";      // #FF2800 (Червоний)
    const string BACKGROUND_DARK_BLUE = "\033[48;2;4;52;88m"; // #043458 (Темно-синій)
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
        return sprintf("%s" . self::BACKGROUND_DARK_BLUE . " INFO " . self::NC . " %s.", '  ', $message);
    }

    public static function warn($message): string
    {
        return sprintf("%s" . self::BACKGROUND_ORANGE . " WARN " . self::NC . " %s.", '  ', $message);
    }

    public static function error($message): string
    {
        return sprintf("%s" . self::BACKGROUND_RED . " ERROR " . self::NC . " %s.", '  ', $message);
    }
}
