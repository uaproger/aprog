<?php

define('BASE_DIR', dirname(__DIR__));
const RED = "\033[0;31m";
const GREEN = "\033[0;32m";
const YELLOW = "\033[0;33m";
const BLUE = "\033[0;34m";
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
