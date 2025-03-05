<?php

namespace Src;

class Property
{
    private static string $property;

    public function __construct($nameProperty, $dir)
    {
        $namespace = Logger::$config['paths']['properties']['namespace'][$dir] ?? '';
        $pathProperty = strtolower($namespace);

        $use = Logger::$config['paths']['properties']['use'][$dir] ?? '';
        $pathBaseClass = str_replace('\\', '/', $use) . '/';

        if (!file_exists($pathBaseClass . 'Kernel.php')) {
            self::baseClass($use);
        }

        self::$property = '<?php'.PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "namespace $namespace;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "use $use\\Kernel;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "class $nameProperty extends Kernel".PHP_EOL;
        self::$property .= "{".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "    # public int \$number = 0; # example property number" . PHP_EOL;
        self::$property .= "    # public ?string \$string = null; # example property string" . PHP_EOL;
        self::$property .= "    # public bool \$boolean = false; # example property boolean" . PHP_EOL;
        self::$property .= "    # public array \$array = []; # example property array" . PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "    public function __construct(object|array \$data = [])".PHP_EOL;
        self::$property .= "    {".PHP_EOL;
        self::$property .= "        parent::__construct(\$data);".PHP_EOL;
        self::$property .= "    }".PHP_EOL;
        self::$property .= "}".PHP_EOL;

        $pathProperty = str_replace('\\', '/', $pathProperty) . '/';
        if (!file_exists($pathProperty) || !is_dir($pathProperty)) {
            mkdir($pathProperty, 0777, true);
        }
        if (!file_exists("{$pathProperty}{$nameProperty}.php")) {
            $res = file_put_contents("{$pathProperty}{$nameProperty}.php", self::$property);
            self::message($nameProperty, $res ? 0 : 2);
        } else {
            self::message($nameProperty);
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

        $pathProperty = str_replace('\\', '/', strtolower($namespace)) . '/';
        if (!file_exists($pathProperty) || !is_dir($pathProperty)) {
            mkdir($pathProperty, 0777, true);
        }
        $res = file_put_contents("{$pathProperty}Kernel.php", self::$property);
        self::message('Kernel', $res ? 0 : 2);
    }

    public static function message(string $nameProperty, int $status = 1): void
    {
        $parent = 'Property';
        $items = 'Items/';
        if ($nameProperty === 'Kernel') {
            $parent = 'батьківський клас';
            $items = '';
        }

        if ($status == 0) {
            echo Logger::info("Створено {$parent} " . Logger::WHITE . Logger::BOLD . "[./app/Properties/{$items}{$nameProperty}]" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення Property " . Logger::WHITE . Logger::BOLD . "[$nameProperty]" . Logger::NC);
        } else {
            echo Logger::warn("Property " . Logger::WHITE . Logger::BOLD . "[./app/Properties/{$items}{$nameProperty}]" .  Logger::NC . " вже існує");
        }

        if ($nameProperty === 'Kernel') {
            echo PHP_EOL . Logger::GREEN . "Було створено батьківський клас `$nameProperty` для правильної роботи `Properties`." . Logger::NC . PHP_EOL;
        } else {
            echo PHP_EOL . Logger::GREEN . "Процес завершено." . Logger::NC;
            echo PHP_EOL . "Дякуємо що обрали " . Logger::GREEN . "aprog" . Logger::NC . ". Слава Україні 🇺🇦!";
            echo PHP_EOL . "Copyright (c) " . date('Y') . " AlexProger.";
        }
    }
}
