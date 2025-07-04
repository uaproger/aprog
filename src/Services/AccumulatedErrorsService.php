<?php

namespace Src\Services;

use Src\Exceptions\AprogException;

/**
 * Aprog Service
 *
 * ######################################
 * --- Клас `AccumulatedErrorsService` для збору помилок ---
 * ######################################
 *
 * Copyright (c) 2025 AlexProger.
 */
class AccumulatedErrorsService
{
    protected array $errors = [];
    protected ?string $trace = null;

    /**
     * Додати помилку до списку
     */
    public function add(string|array $message, ?string $context = null, string|int|null $key = null): void
    {
        if (is_array($message)) {
            foreach ($message as $k => $msg) {
                $fullMessage = $context && !is_array($msg) ? "[{$context}] {$msg}" : $msg;

                if (!is_null($key)) {
                    # Якщо масив, ключі інтерпретуються як дочірні
                    $this->errors[$key][$k] = $fullMessage;
                } else {
                    $this->errors[] = $fullMessage;
                }
            }
        } else {
            $fullMessage = $context ? "[{$context}] {$message}" : $message;

            if (!is_null($key)) {
                $this->errors[$key] = $fullMessage;
            } else {
                $this->errors[] = $fullMessage;
            }
        }
    }

    /**
     * Отримати всі помилки
     */
    public function all(): array
    {
        return $this->errors;
    }

    /**
     * Перевірити, чи є помилки
     */
    public function has(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Очистити список помилок
     */
    public function clear(): void
    {
        $this->errors = [];
    }

    public function addTrace(AprogException $exception): void
    {
        $this->trace = $exception->getFile() . ':' . $exception->getLine();
    }

    public function getTrace(): ?string
    {
        return $this->trace;
    }
}
