<?php

namespace Aprog\Exceptions;

use Exception;
use Aprog\Helpers\Lang;
use Aprog\Services\AccumulatedErrorsService;
use Throwable;

/**
 * @class AprogException
 *
 * Copyright (c) 2025 AlexProger.
 */
class AprogException extends Exception
{
    public function __construct($message = null, $code = 0, Throwable $previous = null, $params = [])
    {
        parent::__construct(__($message), $code, $previous);
        $errors = app(AccumulatedErrorsService::class);
        $errors->addTrace($this);
        if (!is_null($message)) {
            $errors->add(Lang::translations($message, $params));
        } else {
            $errors->add(Lang::translations('AprogException Error!'));
        }
    }
}
