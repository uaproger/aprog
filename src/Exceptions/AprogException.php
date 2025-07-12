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
 * Кастомний Exception для збору та відображення помилок
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
     */
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null, array $params = [])
    {
        parent::__construct(__($message, $params), $code, $previous);
        $errors = app(AccumulatedErrorsService::class);
        $errors->addTrace($this);
        if (!is_null($message)) {
            $errors->add(Lang::translations($message, $params));
        } else {
            $errors->add(Lang::translations('Server Error!'));
        }
    }
}
