<?php

namespace Src;

class Property
{
    private static string $property;

    public function __construct($slashName)
    {
        $namespace = Logger::$config['paths']['properties']['namespace'] ?? '';
        [$namespace, $name] = Logger::seName($namespace, $slashName);

        $use = Logger::$config['paths']['properties']['use'] ?? '';
        $pathBaseClass = str_replace('\\', '/', $use) . '/';

        if (!file_exists($pathBaseClass . 'Kernel.php')) {
            sleep(5);
            self::baseClass($use);
        }

        sleep(5);

        self::$property = '<?php'.PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "namespace $namespace;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "use $use\\Kernel;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "class $name extends Kernel".PHP_EOL;
        self::$property .= "{".PHP_EOL;
        self::$property .= "    # Приклади properties" . PHP_EOL;
        self::$property .= "    # public int \$number = 0;" . PHP_EOL;
        self::$property .= "    # public ?string \$string = null;" . PHP_EOL;
        self::$property .= "    # public bool \$boolean = false;" . PHP_EOL;
        self::$property .= "    # public array \$array = [];" . PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "    /**".PHP_EOL;
        self::$property .= "     * Aprog Service".PHP_EOL;
        self::$property .= "     * ".PHP_EOL;
        self::$property .= "     * ######################################".PHP_EOL;
        self::$property .= "     * --- Клас `$name` для оголошення property ---".PHP_EOL;
        self::$property .= "     * ######################################".PHP_EOL;
        self::$property .= "     * ".PHP_EOL;
        self::$property .= "     * Copyright (c) " . date('Y') . " AlexProger.".PHP_EOL;
        self::$property .= "     */".PHP_EOL;
        self::$property .= "    public function __construct(object|array \$data = [])".PHP_EOL;
        self::$property .= "    {".PHP_EOL;
        self::$property .= "        parent::__construct(\$data);".PHP_EOL;
        self::$property .= "    }".PHP_EOL;
        self::$property .= "}".PHP_EOL;

        $pathProperty = str_replace('\\', '/', lcfirst($namespace)) . '/';
        if (!file_exists($pathProperty) || !is_dir($pathProperty)) {
            mkdir($pathProperty, 0777, true);
        }

        if (!file_exists("{$pathProperty}{$name}.php")) {
            $res = file_put_contents("{$pathProperty}{$name}.php", self::$property);
            self::message($name, $res ? 0 : 2);
        } else {
            self::message($name);
        }
    }

    public static function baseClass($namespace): void
    {
        self::$property = '<?php'.PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "namespace $namespace;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "class Kernel".PHP_EOL;
        self::$property .= "{".PHP_EOL;
        self::$property .= "    public function __construct(array|object \$data = [])".PHP_EOL;
        self::$property .= "    {".PHP_EOL;
        self::$property .= "        foreach (\$data as \$key => \$value) {".PHP_EOL;
        self::$property .= "            \$this->\$key = \$value;".PHP_EOL;
        self::$property .= "        }".PHP_EOL;
        self::$property .= "    }".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "    public function __get(\$name)".PHP_EOL;
        self::$property .= "    {".PHP_EOL;
        self::$property .= "        return \$this->\$name ?? (\$this->defaults[\$name] ?? null);".PHP_EOL;
        self::$property .= "    }".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "    public function __set(\$name, \$value)".PHP_EOL;
        self::$property .= "    {".PHP_EOL;
        self::$property .= "        \$this->\$name = \$value;".PHP_EOL;
        self::$property .= "    }".PHP_EOL;
        self::$property .= "}".PHP_EOL;

        $pathProperty = str_replace('\\', '/', lcfirst($namespace)) . '/';
        if (!file_exists($pathProperty) || !is_dir($pathProperty)) {
            mkdir($pathProperty, 0777, true);
        }
        $res = file_put_contents("{$pathProperty}Kernel.php", self::$property);
        self::message('Kernel', $res ? 0 : 2);
    }

    public static function message(string $name, int $status = 1): void
    {
        $parent = 'Property';
        $items = 'Items/';
        if ($name === 'Kernel') {
            $parent = 'батьківський клас';
            $items = '';
        }

        if ($status == 0) {
            echo Logger::info("Створено {$parent} " . Logger::WHITE . Logger::BOLD . "[./app/Properties/{$items}{$name}]" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення Property " . Logger::WHITE . Logger::BOLD . "[$name]" . Logger::NC);
        } else {
            echo Logger::warn("Property " . Logger::WHITE . Logger::BOLD . "[./app/Properties/{$items}{$name}]" .  Logger::NC . " вже існує");
        }

        if ($name === 'Kernel') {
            echo PHP_EOL . Logger::GREEN . "Було створено батьківський клас `$name` для правильної роботи `Properties`." . Logger::NC . PHP_EOL;
        } else {
            Logger::finishMessage();
        }
    }
}
