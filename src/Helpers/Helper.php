<?php

/**
 * --------------------------------------------------------------------------
 *                              code_location()
 * --------------------------------------------------------------------------
 *
 * Функція `code_location()` дозволяє отримати файл та лінію де її викликають
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('code_location')) {
    function code_location(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? null;

        if ($trace && isset($trace['file'], $trace['line'])) {
            return $trace['file'] . ':' . $trace['line'];
        }

        return 'unknown:0';
    }
}

/**
 * --------------------------------------------------------------------------
 *                                  arr()
 * --------------------------------------------------------------------------
 *
 * Функція `arr()` дозволяє отримати значення масиву, або об'єкта безпечним способом
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('arr')) {
    function arr($array, $key, $default = null)
    {
        if (is_array($array)) {
            return $array[$key] ?? $default;
        }
        if (is_object($array)) {
            return $array->$key ?? $default;
        }
        return null;
    }
}

/**
 * --------------------------------------------------------------------------
 *                                  object()
 * --------------------------------------------------------------------------
 *
 * Функція `object()` дозволяє сформувати об'єкт з масиву, або створювати порожній об'єкт
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('object')) {
    function object(array $data = []): stdClass
    {
        if (!empty($data)) {
            $obj = new stdClass();
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (empty($value)) {
                        $obj->$key = [];
                    } elseif (isset($value[0])) {
                        $obj->$key = array_map(function ($item) {
                            return is_array($item) ? object($item) : $item;
                        }, $value);
                    } else {
                        $obj->$key = object($value);
                    }
                } else {
                    $obj->$key = $value;
                }
            }
            return $obj;
        }
        return new stdClass();
    }
}

/**
 * --------------------------------------------------------------------------
 *                           mail_content_exception()
 * --------------------------------------------------------------------------
 *
 * Функція `mail_content_exception()` дозволяє отримати контент помилки для тіла листа
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('mail_content_exception')) {
    function mail_content_exception(Throwable $exception): string
    {
        return $exception->getMessage() . PHP_EOL . $exception->getTraceAsString();
    }
}
