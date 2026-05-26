<?php

use Aprog\Exceptions\AprogException;
use Aprog\Exceptions\SetAprog;
use Aprog\Mails\MailForDeveloper;
use Aprog\Services\AccumulatedErrorsService;
use Aprog\Services\ArrWrapper;
use Aprog\Services\Gemini;
use Aprog\Services\Telegram;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
    /**
     * @return string
     */
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
    /**
     * @param $array
     * @param $key
     * @param $default
     * @return mixed|null
     */
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
    /**
     * @param array $data
     * @return stdClass
     */
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
    /**
     * @param Throwable $exception
     * @return string
     */
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
    /**
     * @param string $name
     * @param string $header
     * @param string|Throwable $content
     * @param string|null $mail
     * @return MailForDeveloper
     */
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
    /**
     * @param string $ipn
     * @return array
     */
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
    /**
     * @param string|int|null $number
     * @param int $countChars
     * @return string
     */
    function zerosArticle(string|int|null $number = null, int $countChars = 5): string
    {
        return str_pad(trim((string)$number), $countChars, '0', STR_PAD_LEFT);
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
    /**
     * @param $text
     * @return string
     */
    function bold($text): string
    {
        return "<b>$text</b>";
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
    function guid(mixed $data = null): string
    {
        # Якщо нічого не передали — поводимось як UUID v4
        if ($data === null) {
            $bytes = random_bytes(16);
        } else {
            # 1. Нормалізуємо дані (рекурсивно, стабільно)
            $normalized = normalizeGuidData($data);

            # 2. Хешуємо
            $hash = hash('sha256', $normalized, true);

            # 3. Беремо перші 16 байт
            $bytes = substr($hash, 0, 16);
        }

        # UUID version 5 (name-based)
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x50); # version 5
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); # RFC 4122

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(bin2hex($bytes), 4)
        );
    }
}

if (!function_exists('normalizeGuidData')) {
    function normalizeGuidData(mixed $data): string
    {
        if (is_array($data)) {
            ksort($data); # стабільний порядок ключів
            return json_encode(array_map('normalizeGuidData', $data), JSON_UNESCAPED_UNICODE);
        }

        if (is_object($data)) {
            return normalizeGuidData((array) $data);
        }

        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }

        if ($data === null) {
            return 'null';
        }

        return (string) $data;
    }
}

/**
 * --------------------------------------------------------------------------
 *                                  gemini()
 * --------------------------------------------------------------------------
 *
 * Функція `gemini()` дозволяє використовувати чат з AI Gemini
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('gemini')) {
    /**
     * @param string $content
     * @param string $userID
     * @return string
     * @throws ConnectionException
     */
    function gemini(string $content, string $userID = '001'): string
    {
        $gemini = new Gemini($userID);
        return $gemini->ask($content);
    }
}

/**
 * --------------------------------------------------------------------------
 *                            parseCustomMarkup()
 * --------------------------------------------------------------------------
 *
 * Функція `parseCustomMarkup()` дозволяє екранізувати текст з AI чатів як (GPT, Gemini і тд)
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('parseCustomMarkup')) {
    /**
     * @param string $text
     * @return string
     */
    function parseCustomMarkup(string $text): string
    {
        # ```code``` -> <pre><code>...</code></pre>
        $text = preg_replace_callback('/```([\s\S]*?)```/', function ($matches) {
            $code = trim($matches[1]);
            return '<pre style="width: max-content; padding: 2px 8px; color: #333333; background-color: #fdfdfd; border: 0.03125rem solid #dddddd; border-radius: 4px;">' .
                '<code style="font-family: Monospaced, serif; font-style: italic; font-weight: 100; letter-spacing: 2px;">' .
                htmlspecialchars($code) .
                '</code></pre>';
        }, $text);

        # **bold** -> <strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong style="font-weight: 700;">$1</strong>', $text);

        # `inline code` -> <code>
        $text = preg_replace('/`([^`]+?)`/', '<code style="font-family: Monospaced, serif; font-style: italic; font-weight: 100; letter-spacing: 2px;">$1</code>', $text);

        # * list item -> <li>
        $text = preg_replace('/^\* (.+)$/m', '<li style="font-family: Times New Roman, serif; font-weight: 400;">$1</li>', $text);

        # Wrap in base <div>
        return '<div style="font-family: Times New Roman, serif; font-weight: 400;">' . $text . '</div>';
    }
}

/**
 * --------------------------------------------------------------------------
 *                                      ip()
 * --------------------------------------------------------------------------
 *
 * Функція `ip()` дозволяє отримати ip адресу користувача
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('ip')) {
    /**
     * @return string|null
     */
    function ip(): ?string
    {
        $_server = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($_server as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return null;
    }
}

/**
 * --------------------------------------------------------------------------
 *                              uniqueBrowser()
 * --------------------------------------------------------------------------
 *
 * Функція `uniqueBrowser()` дозволяє отримати унікальний id браузера користувача
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('uniqueBrowser')) {
    /**
     * @return string
     */
    function uniqueBrowser(): string
    {
        $userAgent = arr($_SERVER, 'HTTP_USER_AGENT');
        $ip = ip();
        $uniqueIdBrowser = arr($_COOKIE, 'uniqueBrowser', rand(100000, 9999999));
        $salt = 'aprog';

        return sha1($userAgent . $ip . $uniqueIdBrowser . $salt);
    }
}

/**
 * --------------------------------------------------------------------------
 *                                  isPhone()
 * --------------------------------------------------------------------------
 *
 * Функція `isPhone()` дозволяє перевіряти на валідність номерів телефонів
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('isPhone')) {
    /**
     * @param string|null $number
     * @return bool
     */
    function isPhone(?string $number = null): bool
    {
        if (is_null($number)) {
            return false;
        }

        # Має складатися лише з цифр
        if (!ctype_digit($number)) {
            return false;
        }

        # Отримуємо коди телефонів
        $codes = config('phone.codes', ['380']);

        # Перевіряємо по кодах
        foreach ($codes as $code) {
            if (str_starts_with($number, $code) && strlen($number) === strlen($code) + 9) {
                return true;
            }
        }

        return false;
    }
}

/**
 * --------------------------------------------------------------------------
 *  exception()
 * --------------------------------------------------------------------------
 *
 * Функція `exception()` дозволяє викликати кастомні методи для AprogException
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('exception')) {
    /**
     * @param string $class
     * @param string $method
     * @param array $params
     * @param string $type
     * @return AprogException
     * @throws AprogException
     */
    function exception(string $class, string $method, array $params = [], string $type = 'static'): AprogException
    {
        return SetAprog::exception($class, $method, $params, $type);
    }
}

/**
 * --------------------------------------------------------------------------
 *  sanitize_quotes()
 * --------------------------------------------------------------------------
 *
 * Функція `sanitize_quotes()` Видаляє всі види лапок і апострофів з рядка
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('sanitize_quotes')) {
    /**
     * @param string|null $value
     * @return string|null
     */
    function sanitize_quotes(?string $value): ?string
    {
        if (is_null($value)) return null;

        $charsToRemove = [
            '"',  # ASCII "
            "'",  # ASCII '
            '‘',  # U+2018 left single quotation mark
            '’',  # U+2019 right single quotation mark
            '“',  # U+201C left double quotation mark
            '”',  # U+201D right double quotation mark
            '„',  # U+201E double low-9 quotation mark
            '«',  # U+00AB left-pointing double angle quote
            '»',  # U+00BB right-pointing double angle quote
            '`',  # backtick
        ];

        return str_replace($charsToRemove, '', $value);
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 * --------------------------------------------------------------------------
 *  wrap()
 * --------------------------------------------------------------------------
 *
 * Функція `wrap()` Дозволяє безпечно працювати з вкладеними даними
 * також має методи `map`, `filter`, `pluck`, `sort`, `set`
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('wrap')) {
    function wrap(mixed $array = [], string|int|null $key = null, mixed $default = null): ArrWrapper
    {
        $wrapper = new ArrWrapper($array);
        return $key === null ? $wrapper : $wrapper->get($key, $default);
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 * --------------------------------------------------------------------------
 *  telegram()
 * --------------------------------------------------------------------------
 *
 * Функція `telegram()` Дозволяє працювати з telegram
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('telegram')) {
    /**
     * @param string|null $token
     * @return Telegram
     */
    function telegram(?string $token = null): Telegram
    {
        return new Telegram($token);
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 * --------------------------------------------------------------------------
 *  content_exception()
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
if (!function_exists('content_exception')) {
    /**
     * @param Throwable|null $exception
     * @return string
     */
    function content_exception(?Throwable $exception = null): string
    {
        if (is_null($exception)) return code_location();

        $trace = array_slice($exception->getTrace(), 0, 3);
        $accum = AccumulatedErrorsService::init();

        $formattedTrace = array_map(function ($frame, $index) {
            $file = $frame['file'] ?? '[internal]';
            $line = $frame['line'] ?? '??';
            $call = '';
            if (!empty($frame['class'])) {
                $call = "{$frame['class']}->{$frame['function']}()";
            } elseif (!empty($frame['function'])) {
                $call = "{$frame['function']}()";
            }
            return "$index) $file:$line — $call";
        }, $trace, array_keys($trace));

        $traceBlock = PHP_EOL . '<pre>' . htmlspecialchars(implode(PHP_EOL, $formattedTrace)) . '</pre>';
        $messageBlock = '<code>' . htmlspecialchars($exception->getMessage()) . '</code>';

        if (!empty($accum->allErrors())) {
            $messageBlock = '';
            $traceBlock = '';
            foreach ($accum->allErrors() as $error) {
                $message = htmlspecialchars(wrap($error)->getValue('message'));
                $trace = htmlspecialchars(wrap($error)->getValue('trace'));
                $messageBlock = "<code>— $message</code>" . PHP_EOL . "<pre>$trace</pre>" . PHP_EOL . $messageBlock;
            }
        }

        if (!empty($accum->all())) {
            $messages = '';
            $traceBlock = '';
            foreach ($accum->all() as $message) {
                $message = htmlspecialchars(wrap($message)->getValue('uk'));
                $messages .= "<code>— $message</code>" . PHP_EOL;
            }
            $messageBlock = $messages . "<pre>{$accum->getTrace()}</pre>" . PHP_EOL . PHP_EOL . $messageBlock;
        }

        return $messageBlock . $traceBlock;
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   route_logs()
 *  --------------------------------------------------------------------------
 *
 * Функція отримання списку логів
 *
 * Copyright (c) 2026 AlexProger.
 */
if (!function_exists('route_logs')) {
    function route_logs()
    {
        $files = File::files(storage_path('logs'));

        $formatSize = function ($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        };

        return collect($files)->map(function ($file) use ($formatSize) {
            return [
                'name' => $file->getFilename(),
                'size' => $formatSize($file->getSize()),
                'modified' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        });
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   route_log()
 *  --------------------------------------------------------------------------
 *
 * Функція отримання конкретного логу
 *
 * Copyright (c) 2026 AlexProger.
 */
if (!function_exists('route_log')) {
    function route_log(?string $filename)
    {
        $path = storage_path("logs/$filename");
        if (!File::exists($path)) abort(404);

        return response()->file($path);
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   route_remove_log()
 *  --------------------------------------------------------------------------
 *
 * Функція видалення конкретного логу
 *
 * Copyright (c) 2026 AlexProger.
 */
if (!function_exists('route_remove_log')) {
    function route_remove_log(?string $filename)
    {
        if (!$filename) abort(404);

        # Захист від ../
        $filename = basename($filename);

        # Дозволяємо тільки log файли та їх backup/version варіанти
        if (!str_contains($filename, '.log')) abort(404);

        $path = storage_path("logs/{$filename}");
        if (!File::exists($path)) abort(404);

        File::delete($path);

        return response()->json([
            'success' => true,
            'message' => "{$filename} removed",
        ]);
    }
}

/**
 * --- Слава Україні 🇺🇦 ---
 *  --------------------------------------------------------------------------
 *   file_put()
 *  --------------------------------------------------------------------------
 *
 * Функція запису даних у файл
 *
 * Copyright (c) 2026 AlexProger.
 */
if (!function_exists('file_put')) {
    function file_put(string|array|object $content, ?string $file = null, bool $json = true, bool $base = true, bool $public = false): bool|int
    {
        if ($json) {
            $content = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $dir = dirname('json');
            if (!File::exists($dir)) File::makeDirectory($dir, 0777, true);
            $file = $file ?? date('Y_m_d_H_i_s');
            $file = "json/$file.json";
        }
        if ($base) return File::put(base_path($file), $content);
        if ($public) return File::put(public_path($file), $content);
        return File::put($file, $content);
    }
}
