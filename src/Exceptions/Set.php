<?php

namespace Aprog\Exceptions;

use Throwable;

class Set
{
    /**
     * Викликає метод класу з перевірками.
     *
     * @param string $class — повне ім’я класу з namespace.
     * @param string $method — назва методу, який викликається.
     * @param array $params — параметри для методу.
     * @param string $type — 'static' або 'instance'.
     *
     * @throws AprogException
     */
    public static function exception(string $class, string $method, array $params = [], string $type = 'static'): void
    {
        try {
            if (!class_exists($class)) {
                throw new AprogException('Error! Code: CL404', 404, null, [
                    'class' => $class
                ]);
            }

            if (!method_exists($class, $method)) {
                throw new AprogException('Error! Code: MT404', 404, null, [
                    'class' => $class,
                    'method' => $method
                ]);
            }

            match ($type) {
                'static' => $class::$method(...$params),
                'instance' => (new $class())->$method(...$params),
                default => throw new AprogException('Error! Code: TP400', 400, null, [
                    'given_type' => $type
                ]),
            };
        } catch (Throwable $e) {
            throw new AprogException('Error! Code: EX500', 500, $e, [
                'class' => $class,
                'method' => $method,
                'message' => $e->getMessage()
            ]);
        }
    }
}
