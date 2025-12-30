<?php

namespace Aprog;

use App\Services\AccumMessagesService;
use App\Services\AccumulatedRequestService;

class AccumulatorLaravel
{
    private static string $accumulator;

    public function __construct($slashName)
    {
        $namespace = Logger::$config['paths']['accumulators']['namespace'] ?? '';
        [$namespace, $name] = Logger::seName($namespace, $slashName);

        sleep(3);

        self::$accumulator = '<?php' . PHP_EOL;
        self::$accumulator .= PHP_EOL;
        self::$accumulator .= "namespace $namespace;" . PHP_EOL;
        self::$accumulator .= PHP_EOL;
        self::$accumulator .= "/**" . PHP_EOL;
        self::$accumulator .= " * Aprog Accumulator Laravel" . PHP_EOL;
        self::$accumulator .= " * " . PHP_EOL;
        self::$accumulator .= " * ######################################" . PHP_EOL;
        self::$accumulator .= " * --- Клас `$name` для накопичення даних ---" . PHP_EOL;
        self::$accumulator .= " * ######################################" . PHP_EOL;
        self::$accumulator .= " * Акумулятор потрібно зареєструвати у AppServiceProvider.php" . PHP_EOL;
        self::$accumulator .= " * " . PHP_EOL;
        self::$accumulator .= " * Приклад:" . PHP_EOL;
        self::$accumulator .= " * " . PHP_EOL;
        self::$accumulator .= " * public function register(): void";
        self::$accumulator .= " * {" . PHP_EOL;
        self::$accumulator .= " *     \$this->app->singleton($name::class);" . PHP_EOL;
        self::$accumulator .= " * }" . PHP_EOL;
        self::$accumulator .= " * " . PHP_EOL;
        self::$accumulator .= " * Copyright (c) " . date('Y') . " AlexProger." . PHP_EOL;
        self::$accumulator .= " */" . PHP_EOL;
        self::$accumulator .= "final class $name" . PHP_EOL;
        self::$accumulator .= "{" . PHP_EOL;
        self::$accumulator .= "    private mixed \$data = null;" . PHP_EOL;
        self::$accumulator .= PHP_EOL;
        self::$accumulator .= "    public static function init(): $name" . PHP_EOL;
        self::$accumulator .= "    {" . PHP_EOL;
        self::$accumulator .= "        return app($name::class);" . PHP_EOL;
        self::$accumulator .= "    }" . PHP_EOL;
        self::$accumulator .= PHP_EOL;
        self::$accumulator .= "    public function reset(): void" . PHP_EOL;
        self::$accumulator .= "    {" . PHP_EOL;
        self::$accumulator .= "        \$this->data = null;" . PHP_EOL;
        self::$accumulator .= "    }" . PHP_EOL;
        self::$accumulator .= "}" . PHP_EOL;

        $pathAccumulator = str_replace('\\', '/', lcfirst($namespace)) . '/';
        if (!file_exists($pathAccumulator) || !is_dir($pathAccumulator)) {
            mkdir($pathAccumulator, 0777, true);
        }

        if (!file_exists("{$pathAccumulator}{$name}.php")) {
            $res = file_put_contents("{$pathAccumulator}{$name}.php", self::$accumulator);
            self::message($pathAccumulator . $name, $res ? 0 : 2);
        } else {
            self::message($pathAccumulator . $name);
        }
    }

    public static function message(string $name, int $status = 1): void
    {
        if ($status == 0) {
            echo Logger::info("Створено акумулятор для Laravel " . Logger::GREEN . Logger::BOLD . "[./$name]" . Logger::NC);
        } elseif ($status == 2) {
            echo Logger::error("Помилка створення акумулятора для Laravel " . Logger::RED . Logger::BOLD . "[$name]" . Logger::NC);
        } else {
            echo Logger::warn("Акумулятор для Laravel " . Logger::ORANGE . Logger::BOLD . "[./$name]" . Logger::NC . " вже існує");
        }

        Logger::finishMessage();
    }
}
