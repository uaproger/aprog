<?php

namespace Uaproger\Aprog\Src;

class Property
{
    private static string $property;

    public function __construct($nameProperty = 'Example', $dir = 'base')
    {
        $namespace = Logger::$config['paths']['properties']['namespace'][$dir] ?? '';
        $pathProperty = strtolower($namespace);

        $extendClass = $dir === 'base' ? 'Core' : 'Kernel';

        $use = Logger::$config['paths']['properties']['use'][$dir] ?? '';
        $pathBaseClass = str_replace('\\', '/', $use) . '/';

        if (!file_exists($pathBaseClass . $extendClass . '.php')) {
            self::baseClass($use, $extendClass);
        }

        self::$property = '<?php'.PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "namespace $namespace;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "use $use\\$extendClass;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "class $nameProperty extends $extendClass".PHP_EOL;
        self::$property .= "{".PHP_EOL;
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

    public static function baseClass($namespace, $class): void
    {
        self::$property = '<?php'.PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "namespace $namespace;".PHP_EOL;
        self::$property .= PHP_EOL;
        self::$property .= "class $class".PHP_EOL;
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
        $res = file_put_contents("{$pathProperty}{$class}.php", self::$property);
        self::message($class, $res ? 0 : 2);
    }

    public static function message(string $nameProperty, int $status = 1): void
    {
        if ($status == 0) {
            echo Logger::info("Створено Property " . Logger::GREEN . Logger::BOLD . "`" . $nameProperty . "`" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення Property " . Logger::RED . Logger::BOLD . "`" . $nameProperty . "`" . Logger::NC);
        } else {
            echo Logger::warn("Property " . Logger::BLUE . Logger::BOLD . "`" . $nameProperty . "`" .  Logger::NC . " вже існує" . Logger::NC);
        }
        echo PHP_EOL . Logger::GREEN . "Finish process." . Logger::NC;
        echo PHP_EOL . "Дякую що обрали " . Logger::GREEN . "`aprog`" . Logger::NC . ". Since 2025";
    }
}
