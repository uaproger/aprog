<?php

namespace Aprog\Accumulators;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * Aprog Accumulator
 *
 * --------------------------------------------------------------------------
 *  Клас `LogAccumulator` для накопичення даних
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
final class LogAccumulator
{
    private static ?self $instance = null;
    private array $data = [];
    private array $alphabet = ['A', 'B', 'C'];
    private int $length = 3;
    private array $indexes = [];

    private function __construct()
    {
        $this->alphabet = config('app.log.alphabet', $this->alphabet);
        $this->length = config('app.log.length', $this->length);
        $this->indexes = array_fill(0, $this->length, 0);
    }

    public static function init(): LogAccumulator
    {
        return self::$instance ??= new LogAccumulator();
    }

    public function add(string $key, string|array $log): LogAccumulator
    {
        $hash = $this->currentHash();
        $this->data["[$hash]$key"] = $log;
        $this->next();

        return $this;
    }

    public function print(): void
    {
        blockInfo(code_location(), $this->data);
        $this->reset();
    }

    public function reset(): void
    {
        self::$instance = null;
        $this->data = [];
    }

    private function currentHash(): string
    {
        return implode('', array_map(
            fn ($i) => $this->alphabet[$i],
            $this->indexes
        ));
    }

    private function next(): void
    {
        $base = count($this->alphabet);

        for ($i = $this->length - 1; $i >= 0; $i--) {
            $this->indexes[$i]++;

            if ($this->indexes[$i] < $base) {
                return;
            }

            $this->indexes[$i] = 0;
        }
    }
}
