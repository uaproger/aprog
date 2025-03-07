<?php

namespace Src;

class Service
{
    private static string $service;
    
    public function __construct($slashName)
    {
        $namespace = Logger::$config['paths']['services']['namespace'] ?? '';
        [$namespace, $name] = Logger::seName($namespace, $slashName);

        sleep(5);

        self::$service = '<?php'.PHP_EOL;
        self::$service .= PHP_EOL;
        self::$service .= "namespace $namespace;".PHP_EOL;
        self::$service .= PHP_EOL;
        self::$service .= "/**".PHP_EOL;
        self::$service .= " * Aprog Service".PHP_EOL;
        self::$service .= " * ".PHP_EOL;
        self::$service .= " * ######################################".PHP_EOL;
        self::$service .= " * --- Клас `$name` для розробки сервісу ---".PHP_EOL;
        self::$service .= " * ######################################".PHP_EOL;
        self::$service .= " * ".PHP_EOL;
        self::$service .= " * Copyright (c) " . date('Y') . " AlexProger.".PHP_EOL;
        self::$service .= " */".PHP_EOL;
        self::$service .= "class $name".PHP_EOL;
        self::$service .= "{".PHP_EOL;
        self::$service .= "    public function __construct()".PHP_EOL;
        self::$service .= "    {".PHP_EOL;
        self::$service .= "        // code...".PHP_EOL;
        self::$service .= "    }".PHP_EOL;
        self::$service .= "}".PHP_EOL;

        $pathService = str_replace('\\', '/', lcfirst($namespace)) . '/';
        if (!file_exists($pathService) || !is_dir($pathService)) {
            mkdir($pathService, 0777, true);
        }

        if (!file_exists("{$pathService}{$name}.php")) {
            $res = file_put_contents("{$pathService}{$name}.php", self::$service);
            self::message($name, $res ? 0 : 2);
        } else {
            self::message($name);
        }
    }

    public static function message(string $name, int $status = 1): void
    {
        if ($status == 0) {
            echo Logger::info("Створено сервіс " . Logger::WHITE . Logger::BOLD . "[./app/Services/$name]" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення сервісу " . Logger::WHITE . Logger::BOLD . "[$name]" . Logger::NC);
        } else {
            echo Logger::warn("Сервіс " . Logger::WHITE . Logger::BOLD . "[./app/Services/$name]" .  Logger::NC . " вже існує");
        }

        Logger::finishMessage();
    }
}
