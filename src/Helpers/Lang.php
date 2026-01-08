<?php

namespace Aprog\Helpers;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * Lang helper
 *
 * --------------------------------------------------------------------------
 *          Хелпер для отримання всіх перекладів за одним ключем
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class Lang
{
    /**
     * --- Метод отримання всіх перекладів ---
     * @param string $key
     * @param array $params
     * @return array
     */
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

    /**
     * --- Парсер перекладів ---
     * @param array $params
     * @param string $locale
     * @return array
     */
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
