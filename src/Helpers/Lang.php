<?php

namespace Aprog\Helpers;

/**
 * @class Lang
 *
 * --------------------------------------------------------------------------
 * --- Хелпер для отримання всіх перекладів за одним ключем ---
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class Lang
{
    public static function translations(string $key, array $params = []): array
    {
        $locales = config('accum.locales', ['uk', 'en']);
        $translations = [];

        foreach ($locales as $locale) {
            $translatedParams = self::translateParams($params, $locale);
            $translations[$locale] = __($key, $translatedParams, $locale);
        }

        return [$translations];
    }

    private static function translateParams(array $params, string $locale): array
    {
        $translated = [];

        foreach ($params as $paramKey => $paramValue) {
            if (is_array($paramValue)) {
                $translated[$paramKey] = self::translateParams($paramValue, $locale);
            } elseif (is_string($paramValue)) {
                $translated[$paramKey] = __($paramValue, [], $locale);
            } else {
                $translated[$paramKey] = $paramValue;
            }
        }

        return $translated;
    }
}
