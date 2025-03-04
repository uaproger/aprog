<?php

namespace Src;

class Logger
{
    const string RED = "\033[0;31m";
    const string GREEN = "\033[0;32m";
    const string YELLOW = "\033[0;33m";
    const string BLUE = "\033[0;34m";
    const string BACKGROUND_BLUE = "\033[48;2;0;0;255m";   // Синій фон
    const string BACKGROUND_RED = "\033[48;2;255;0;0m";    // Червоний фон
    const string BACKGROUND_YELLOW = "\033[48;2;255;255;0m"; // Жовтий фон
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
        return sprintf("%s" . self::BACKGROUND_BLUE . " INFO " . self::NC . "%s.", '  ', $message);
    }

    public static function warn($message): string
    {
        return sprintf("%s" . self::BACKGROUND_YELLOW . " WARN " . self::NC . "%s.", '  ', $message);
    }

    public static function error($message): string
    {
        return sprintf("%s" . self::BACKGROUND_RED . " ERROR " . self::NC . "%s.", '  ', $message);
    }
}
