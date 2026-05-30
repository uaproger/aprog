<?php

namespace Aprog\Exceptions;

use Exception;
use Aprog\Helpers\Lang;
use Aprog\Services\AccumulatedErrorsService;
use Throwable;

/**
 * AprogException
 *
 * --------------------------------------------------------------------------
 *          Кастомний Exception для збору та відображення помилок
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class AprogException extends Exception
{
    /**
     * @param string|null $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $params
     * @param bool $print
     */
    public function __construct(
        string $message = null,
        int $code = 0,
        ?Throwable $previous = null,
        array $params = [],
        bool $print = true
    )
    {
        if (!is_null($message)) {
            parent::__construct(__($message, $params), $code, $previous);
            $errors = AccumulatedErrorsService::init();
            $errors->addTrace($this);
            $errors->add(Lang::translations($message, $params));
        } else {
            parent::__construct($message, $code, $previous);
        }

        # Прінтемо логи якщо вони є
        if ($print) bugger()->add('Aprog Exception', __($message, $params), $print)->print();
    }
}
