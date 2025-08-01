<?php

namespace Aprog\Exceptions;

/**
 * class SetAprog
 *
 * --------------------------------------------------------------------------
 *  `SetAprog`
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class SetAprog
{
    /**
     * --------------------------------------------------------------------------
     *  Викликає метод класу з перевірками.
     * --------------------------------------------------------------------------
     *
     * @param string $class — повне ім’я класу з namespace.
     * @param string $method — назва методу, який викликається (Attention: метод має повертати `new AprogException()`).
     * @param array $params — параметри для методу.
     * @param string $type — 'static' або 'instance'.
     * @return AprogException
     * @throws AprogException
     */
    public static function exception(string $class, string $method, array $params = [], string $type = 'static'): AprogException
    {
        if (!class_exists($class)) {
            return new AprogException('Error! Code: CL404', 404, null, [
                'class' => $class
            ]);
        }

        if (!method_exists($class, $method)) {
            return new AprogException('Error! Code: MT404', 404, null, [
                'class' => $class,
                'method' => $method
            ]);
        }

        return match ($type) {
            'static' => $class::$method(...$params),
            'instance' => (new $class())->$method(...$params),
            default => new AprogException('Error! Code: TP400', 400, null, [
                'given_type' => $type
            ]),
        };
    }
}
