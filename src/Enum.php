<?php

namespace Aprog;

class Enum
{
    private static string $enum;

    public function __construct($slashName, string $type = 'string')
    {
        $namespace = Logger::$config['paths']['enums']['namespace'] ?? '';
        [$namespace, $name] = Logger::seName($namespace, $slashName);

        sleep(3);

        self::$enum = '<?php' . PHP_EOL;
        self::$enum .= PHP_EOL;
        self::$enum .= "namespace $namespace;" . PHP_EOL;
        self::$enum .= PHP_EOL;
        self::$enum .= "/**" . PHP_EOL;
        self::$enum .= " * Aprog Enum" . PHP_EOL;
        self::$enum .= " * " . PHP_EOL;
        self::$enum .= " * ######################################" . PHP_EOL;
        self::$enum .= " * --- `$name` ---" . PHP_EOL;
        self::$enum .= " * ######################################" . PHP_EOL;
        self::$enum .= " * " . PHP_EOL;
        self::$enum .= " * Copyright (c) " . date('Y') . " AlexProger." . PHP_EOL;
        self::$enum .= " */" . PHP_EOL;
        self::$enum .= "enum $name: $type"  . PHP_EOL;
        self::$enum .= "{" . PHP_EOL;
        self::$enum .= "    # case DEFAULT_ENUM = 'enum';" . PHP_EOL;
        self::$enum .= "}" . PHP_EOL;

        $pathEnum = str_replace('\\', '/', lcfirst($namespace)) . '/';
        if (!file_exists($pathEnum) || !is_dir($pathEnum)) {
            mkdir($pathEnum, 0777, true);
        }

        if (!file_exists("{$pathEnum}{$name}.php")) {
            $res = file_put_contents("{$pathEnum}{$name}.php", self::$enum);
            self::message($pathEnum . $name, $res ? 0 : 2);
        } else {
            self::message($pathEnum . $name);
        }
    }

    public static function message(string $name, int $status = 1): void
    {
        if ($status == 0) {
            echo Logger::info("Створено Enum " . Logger::GREEN . Logger::BOLD . "[./$name]" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення Enum " . Logger::RED . Logger::BOLD . "[$name]" . Logger::NC);
        } else {
            echo Logger::warn("Enum " . Logger::ORANGE . Logger::BOLD . "[./$name]" . Logger::NC . " вже існує");
        }

        Logger::finishMessage();
    }
}
