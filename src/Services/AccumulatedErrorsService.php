<?php

namespace Aprog\Services;

use Aprog\Exceptions\AprogException;

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
     * --- Оголошення об'єкта класу AccumulatedErrorsService::class ---
     * @return AccumulatedErrorsService
     */
    public static function init(): AccumulatedErrorsService
    {
        return app(AccumulatedErrorsService::class);
    }

    /**
     * --- Додати помилку до списку ---
     * @return void
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
     * --- Отримати всі помилки ---
     * @return array
     */
    public function all(): array
    {
        return $this->errors;
    }

    /**
     * --- Перевірити, чи є помилки ---
     * @return bool
     */
    public function has(): bool
    {
        return !empty($this->errors);
    }

    /**
     * --- Очистити список помилок ---
     * @return void
     */
    public function clear(): void
    {
        $this->errors = [];
    }

    /**
     * --- Додавання посилання на файл та строку ---
     * @param AprogException $exception
     * @return void
     */
    public function addTrace(AprogException $exception): void
    {
        $this->trace = $exception->getFile() . ':' . $exception->getLine();
    }

    /**
     * --- Отримання посилання на файл та строку ---
     * @return string|null
     */
    public function getTrace(): ?string
    {
        return $this->trace;
    }
}
