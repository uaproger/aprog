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
    private static array $instances = [];
    private array $data = [];
    private array $errors = [];
    private array $alphabet = ['A', 'B', 'C'];
    private int $length = 3;
    private array $indexes = [];
    private int $index = 0;

    private function __construct(
        private readonly string $name = 'default'
    ) {
        $this->alphabet = config('app.log.alphabet', $this->alphabet);
        $this->length = config('app.log.length', $this->length);
        $this->indexes = array_fill(0, $this->length, 0);
    }

    public static function init(string $name = 'default'): LogAccumulator
    {
        return self::$instances[$name] ??= new self($name);
    }

    public function add(string $key, mixed $log = null, bool $throwable = false): LogAccumulator
    {
        $hash = $this->currentHash();
        $newKey = $this->validateKey($key);
        $symbol = $throwable ? '❌' : '';
        $this->data["[$hash]$symbol$newKey"] = $log ?? '#####';
        $this->next();

        return $this;
    }

    public function addError(string $key = 'Error', mixed $error = null): LogAccumulator
    {
        $newKey = $this->validateKey($key);
        $this->errors["[$this->index]$newKey"] = $error ?? '#####';
        $this->index++;

        return $this;
    }

    public function print(): void
    {
        $trace = code_location();
        if (!empty($this->data)) blockInfo("$this->name | $trace", $this->data);
        $this->echo(false, $trace);
        $this->reset();
    }

    public function echo(bool $resetInstance = false, ?string $trace = null): LogAccumulator
    {
        if (!empty($this->errors)) {
            $trace = $trace ?? code_location();
            blockLogError("$this->name | $trace", $this->errors);
            $this->resetError($resetInstance);
        }

        return $this;
    }

    public function reset(): void
    {
        unset(self::$instances[$this->name]);
        $this->data = [];
        $this->errors = [];
        $this->indexes = array_fill(0, $this->length, 0);
        $this->index = 0;
    }

    public function resetError(bool $resetInstance = false): void
    {
        if ($resetInstance) unset(self::$instances[$this->name]);
        $this->errors = [];
        $this->index = 0;
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
            if ($this->indexes[$i] < $base) return;

            $this->indexes[$i] = 0;
        }
    }

    private function validateKey(string $key): string
    {
        $exist = str_contains($key, '[') && str_contains($key, ']');
        $existBold = str_contains($key, '<b>') && str_contains($key, '</b>');
        $newKey = $exist ? $key : "[$key]";
        return $existBold ? strtoupper($newKey) : bold(strtoupper($newKey));
    }
}
