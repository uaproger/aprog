<?php

use Aprog\Exceptions\AprogException;
use Aprog\Mails\MailForDeveloper;
use Illuminate\Support\Facades\Log;
use Random\RandomException;

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

/**
 * --------------------------------------------------------------------------
 *                              mail_for_developer()
 * --------------------------------------------------------------------------
 *
 * Функція `mail_for_developer()` дозволяє формувати `MailForDeveloper` лист
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('mail_for_developer')) {
    function mail_for_developer(string $name, string $header, string|Throwable $content, ?string $mail = null): MailForDeveloper
    {
        if ($content instanceof Throwable) {
            $content = mail_content_exception($content);
        }
        return new MailForDeveloper($name, $header, $content, $mail);
    }
}

/**
 * --------------------------------------------------------------------------
 *                                 parse_ipn()
 * --------------------------------------------------------------------------
 *
 * Функція `parse_ipn()` дозволяє визначити дату народження та стать за ІПН
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('parse_ipn')) {
    function parse_ipn(string $ipn): array
    {
        # Базова перевірка: має бути 10 цифр
        if (!preg_match('/^\d{10}$/', $ipn)) {
            return [
                'ІПН' => $ipn,
                'Дата народження' => null,
                'Стать' => null,
                'Помилка' => 'ІПН має складатися з 10 цифр'
            ];
        }

        # Обчислення кількості днів і дати народження
        $days = intval(substr($ipn, 0, 5)) - 1;
        $birthDate = (new DateTime('1900-01-01'))->modify("+$days days");
        $birthDateFormatted = $birthDate->format('d.m.Y');

        # Визначення статі
        $genderDigit = intval($ipn[8]);
        $gender = ($genderDigit % 2 === 0) ? 'Жіноча' : 'Чоловіча';

        return [
            'ІПН' => $ipn,
            'Дата народження' => $birthDateFormatted,
            'Стать' => $gender,
        ];
    }
}

/**
 * --------------------------------------------------------------------------
 *                               zerosArticle()
 * --------------------------------------------------------------------------
 *
 * Функція `zerosArticle()` для формування артикулів від 5 символів з 0-ми попереду
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('zerosArticle')) {
    function zerosArticle($number): string
    {
        return str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}

/**
 * --------------------------------------------------------------------------
 *                                 bold()
 * --------------------------------------------------------------------------
 *
 * Функція `bold()` дозволяє обгортати текст у тег <b></b>
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('bold')) {
    function bold($text): string
    {
        return "<b>$text</b>";
    }
}

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
if (! function_exists('blockInfo')) {
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
    function blockExceptionError(Exception|AprogException|Throwable $exception): void
    {
        blockLogError($exception->getFile() . ':' . $exception->getLine(), $exception->getMessage());
    }
}

/**
 * --------------------------------------------------------------------------
 *                                   guid()
 * --------------------------------------------------------------------------
 *
 * Функція `guid()` дозволяє формувати GUID - ідентифікатори версії (v4) і варіанту (RFC 4122)
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('guid')) {
    /**
     * @throws RandomException
     */
    function guid(): string
    {
        $data = random_bytes(16);
        # Установлюємо версію (v4) і варіант (RFC 4122)
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); # версія 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); # варіант RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
