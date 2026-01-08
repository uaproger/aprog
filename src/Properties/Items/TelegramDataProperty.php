<?php

namespace Aprog\Properties\Items;

use Aprog\Properties\Kernel;

/**
 * Aprog Service
 *
 * --------------------------------------------------------------------------
 *  Клас `TelegramDataProperty` для оголошення property
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class TelegramDataProperty extends Kernel
{
    public ?int $id = null;
    public ?bool $is_bot = false;
    public ?string $first_name = null;
    public ?string $username = null;
    public ?string $type = null;

    public function __construct(object|array $data = [])
    {
        parent::__construct($data);
    }
}
