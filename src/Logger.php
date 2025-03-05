<?php

namespace Src;

class Logger
{
    const WHITE = "\x1b[38;2;255;255;255m"; # Білий колір тексту (RGB)
    const DARK_BLUE = "\x1b[38;2;4;52;88m"; #043458
    const RED = "\x1b[38;2;255;40;0m"; #FF2800
    const ORANGE = "\x1b[38;2;255;136;0m"; #FF8800
    const GREEN = "\x1b[38;2;0;255;0m"; #green
    const WHITE_BG = "\x1b[48;2;255;255;255m"; #ffffff
    const DARK_BLUE_BG = "\x1b[48;2;4;52;88m"; #043458
    const RED_BG = "\x1b[48;2;255;40;0m"; #FF2800
    const ORANGE_BG = "\x1b[48;2;255;136;0m"; #FF8800
    const GREEN_BG = "\x1b[48;2;0;255;0m"; #green
    const BOLD = "\x1b[1m";  # Жирний текст
    const NC = "\x1b[0m"; # Скидає форматування (нормальний колір та стиль)

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
        return PHP_EOL . sprintf("%s" . self::DARK_BLUE_BG . Logger::WHITE . " INFO " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }

    public static function warn($message): string
    {
        return PHP_EOL . sprintf("%s" . self::ORANGE_BG . Logger::WHITE . " WARN " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }

    public static function error($message): string
    {
        return PHP_EOL . sprintf("%s" . self::RED_BG . Logger::WHITE . " ERROR " . self::NC . " %s.", '  ', $message) . PHP_EOL;
    }
}
