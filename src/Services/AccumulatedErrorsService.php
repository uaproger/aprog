<?php

namespace Aprog\Services;

use Aprog\Exceptions\AprogException;
use Exception;
use Throwable;

/**
 * Aprog Service
 *
 * --------------------------------------------------------------------------
 * Сервіс AccumulatedErrorsService для накопичення помилок
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class AccumulatedErrorsService
{
    protected array $errors = [];
    protected array $messages = [];
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
     * --- Додати повідомлення до списку ---
     * @param string|array $message
     * @param string|null $context
     * @param string|int|null $key
     * @return void
     */
    public function add(string|array $message, ?string $context = null, string|int|null $key = null): void
    {
        if (is_array($message)) {
            foreach ($message as $k => $msg) {
                $fullMessage = $context && !is_array($msg) ? "[{$context}] {$msg}" : $msg;

                if (!is_null($key)) {
                    # Якщо масив, ключі інтерпретуються як дочірні
                    $this->messages[$key][$k] = $fullMessage;
                } else {
                    $this->messages[] = $fullMessage;
                }
            }
        } else {
            $fullMessage = $context ? "[{$context}] {$message}" : $message;

            if (!is_null($key)) {
                $this->messages[$key] = $fullMessage;
            } else {
                $this->messages[] = $fullMessage;
            }
        }
    }

    /**
     * --- Отримати всі повідомлення ---
     * @return array
     */
    public function all(): array
    {
        return $this->messages;
    }

    /**
     * --- Перевірити, чи є повідомлення ---
     * @return bool
     */
    public function has(): bool
    {
        return !empty($this->messages);
    }

    /**
     * --- Очистити список повідомлень ---
     * @return void
     */
    public function clear(): void
    {
        $this->messages = [];
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

    /**
     * --- Додати помилки до списку ---
     * @param AprogException|Exception|Throwable $exception
     * @return void
     */
    public function addError(AprogException|Exception|Throwable $exception): void
    {
        $this->errors[] = [
            'message' => $exception->getMessage(),
            'trace' => $exception->getFile() . ':' . $exception->getLine(),
        ];

        if (!$this->has())
            $this->add('Please contact your support!', 'Server Error!');
    }

    /**
     * --- Отримати всі помилки ---
     * @return array
     */
    public function allErrors(): array
    {
        return $this->errors;
    }
}
