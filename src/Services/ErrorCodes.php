<?php

namespace Aprog\Services;

use Aprog\Models\ErrorCode;

/**
 * class ErrorCodes
 *
 * --------------------------------------------------------------------------
 *                  Сервіс для роботи з кодами помилок
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class ErrorCodes
{
    protected static array $cache = [];

    /**
     * --- Отримуємо помилку ---
     * @param string $code
     * @param string $default
     * @return string
     */
    public static function get(string $code, string $default = 'Невідома помилка'): string
    {
        if (empty(self::$cache)) {
            self::loadCache();
        }

        return self::$cache[$code] ?? $default;
    }

    /**
     * --- Отримуємо всі помилки ---
     * @return array
     */
    public static function getAll(): array
    {
        if (empty(self::$cache)) {
            self::loadCache();
        }

        return self::$cache;
    }

    /**
     * --- Завантажуємо помилки з кешу ---
     * @return void
     */
    protected static function loadCache(): void
    {
        self::$cache = ErrorCode::query()
            ->pluck('description', 'code')
            ->toArray();
    }

    /**
     * --- Очищаємо кеш помилок ---
     * @return void
     */
    public static function refresh(): void
    {
        self::$cache = [];
    }
}
