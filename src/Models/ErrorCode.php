<?php

namespace Aprog\Models;

use Aprog\Enum\TableNameEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * class ErrorCode
 *
 * --------------------------------------------------------------------------
 *                              Модель ErrorCode
 * --------------------------------------------------------------------------
 *
 * @property string code
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * Copyright (c) 2025 AlexProger.
 * @method static updateOrCreate(array $array, string[] $array1)
 * @method static create(array $array)
 * @method static find(mixed $column)
 * @method static query()
 */
class ErrorCode extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $fillable = [
        'code',
        'description',
    ];
    protected $table = TableNameEnum::ERROR_CODES;


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
     * --- Додати нову помилку ---
     * @param string $code
     * @param string $description
     * @return string
     */
    public static function add(string $code, string $description): string
    {
        ErrorCode::updateOrCreate(
            ['code' => $code],
            ['description' => $description]
        );

        return $description;
    }

    /**
     * --- Отримуємо всі помилки ---
     * @return array
     */
    public static function all($columns = ['*']): array
    {
        if (empty(self::$cache)) {
            self::loadCache();
        }

        return self::$cache;
    }

    /**
     * --- Очищаємо кеш помилок ---
     * @return void
     */
    public static function clear(): void
    {
        self::$cache = [];
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
}
