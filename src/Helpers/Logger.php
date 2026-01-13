<?php

use Aprog\Accumulators\LogAccumulator;
use Illuminate\Support\Facades\Log;

/**
 * --------------------------------------------------------------------------
 *                             blockLogError()
 * --------------------------------------------------------------------------
 *
 * Функція `blockLogError()` дозволяє записувати помилкові логи одним блоком
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('blockLogError')) {
    /**
     * @param string $url
     * @param string|array|object $message
     * @return void
     */
    function blockLogError(string $url, string|array|object $message = 'err-except'): void
    {
        if ($message === 'err-except') {
            $message = $url;
            $url = code_location();
        }
        Log::error(PHP_EOL);
        Log::error(bold('❌ BLOCK ERROR START'));
        Log::error(bold($url));
        if (is_array($message) || is_object($message)) {
            foreach ($message as $key => $value) {
                Log::error("$key => " . json_encode($value, JSON_UNESCAPED_UNICODE));
            }
        } else {
            Log::error($message);
        }
        Log::error(bold('❌ BLOCK ERROR END'));
        Log::error(PHP_EOL);
    }
}

/**
 * --------------------------------------------------------------------------
 *                             blockInfo()
 * --------------------------------------------------------------------------
 *
 * Функція `blockInfo()` дозволяє записувати інформаційні логи одним блоком
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('blockInfo')) {
    /**
     * @param string $url
     * @param string|array|object $message
     * @return void
     */
    function blockInfo(string $url, string|array|object $message = 'err-except'): void
    {
        if ($message === 'err-except') {
            $message = $url;
            $url = code_location();
        }
        Log::info(PHP_EOL);
        Log::info(bold('✔️ BLOCK INFO START'));
        Log::info(bold($url));
        if (is_array($message) || is_object($message)) {
            foreach ($message as $key => $value) {
                Log::info("$key => " . json_encode($value, JSON_UNESCAPED_UNICODE));
            }
        } else {
            Log::info($message);
        }
        Log::info(bold('✔️ BLOCK INFO END'));
        Log::info(PHP_EOL);
    }
}

/**
 * --------------------------------------------------------------------------
 *                             blockExceptionError()
 * --------------------------------------------------------------------------
 *
 * Функція `blockExceptionError()` дозволяє записувати помилкові Exception логи одним блоком
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('blockExceptionError')) {
    /**
     * @param Exception|AprogException|Throwable $exception
     * @return void
     */
    function blockExceptionError(Exception|AprogException|Throwable $exception): void
    {
        blockLogError($exception->getFile() . ':' . $exception->getLine(), $exception->getMessage());
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   bugger()
 *  --------------------------------------------------------------------------
 *
 * Довжина hash, дефолтно встановлюється у config - app.log.length
 *
 * Copyright (c) 2026 AlexProger.
 */
if (!function_exists('bugger')) {
    function bugger(?string $key = null, mixed $log = null): LogAccumulator
    {
        $bugger = LogAccumulator::init();

        if (!is_null($key) && !is_null($log)) return $bugger->add($key, $log);

        return $bugger;
    }
}
