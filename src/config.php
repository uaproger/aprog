<?php

define('BASE_DIR', dirname(__DIR__));
const TEXT = "\033[0;30m";
const RED = "\033[0;31m";
const GREEN = "\033[0;32m";
const YELLOW = "\033[0;33m";
const BLUE = "\033[0;34m";
const BACKGROUND_DEFAULT = "\033[49m";
const BACKGROUND_RED = "\033[48;5;196m";
const BACKGROUND_GREEN = "\033[48;5;46m";
const BACKGROUND_YELLOW = "\033[48;5;226m";
const BACKGROUND_BLUE = "\033[48;5;21m";
const BACKGROUND_BLACK = "\033[48;5;16m";
const BACKGROUND_WHITE = "\033[48;5;15m";
const BOLD = "\033[1m";
const ITALIC = "\033[3m";
const NC = "\033[0m";

$config = [
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

function info($message): string
{
    return sprintf('%s<info> INFO </info> %s.', '  ', $message);
}

function warn($message): string
{
    return sprintf('%s<warn> WARN </warn> %s.', '  ', $message);
}

function error($message): string
{
    return sprintf('%s<error> ERROR </error> %s.', '  ', $message);
}
