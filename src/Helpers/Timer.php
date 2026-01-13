<?php

use Aprog\Services\ArrWrapper;
use SebastianBergmann\Timer\Timer;

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   timeStart()
 *  --------------------------------------------------------------------------
 *
 * Функція запуска таймера
 *
 * Copyright (c) 2026 AlexProger.
 */
if (! function_exists('timeStart')) {
    function timeStart(): Timer
    {
        $timer = new Timer();
        $timer->start();
        return $timer;
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   timeStop()
 *  --------------------------------------------------------------------------
 *
 * Функція зупинки та повернення таймера [формат - 00:00:00.000]
 *
 * Copyright (c) 2026 AlexProger.
 */
if (! function_exists('timeStop')) {
    function timeStop(Timer $timer): string
    {
        return $timer->stop()->asString();
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 * --------------------------------------------------------------------------
 *  checkMemory()
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('checkMemory')) {
    function checkMemory(?int $start = null, ?string $label = null, bool $usage = false, bool $peak = true): int|array
    {
        if (is_null($start)) return memory_get_usage($usage);

        $data = [
            'used_mb' => round((memory_get_usage($usage) - $start) / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage($peak) / 1024 / 1024, 2),
            'limit_mb' => (int)str_replace('M', '', ini_get('memory_limit'))
        ];

        if ($label) $data['label'] = $label;

        return $data;
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 * --------------------------------------------------------------------------
 *  tm()
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('tm')) {
    function tm(?Timer $timer = null, ?int $start = null): ArrWrapper|string|array
    {
        if (is_null($timer) && is_null($start)) {
            $timer = timeStart();
            $memory = checkMemory();
            return wrap(['timer' => $timer, 'memory' => $memory]);
        }

        if (!is_null($timer) && is_null($start)) return timeStop($timer);
        if (!is_null($start) && is_null($timer)) return checkMemory($start);

        return wrap(['timer' => timeStop($timer), 'memory' => checkMemory($start)]);
    }
}
