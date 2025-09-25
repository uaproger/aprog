<?php

namespace Aprog\Services;

use stdClass;

class ArrWrapper
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Отримати вкладене значення по ключу
     */
    public function get(string|int $key, mixed $default = null): static
    {
        if (is_array($this->value) && array_key_exists($key, $this->value)) {
            return new static($this->value[$key]);
        }

        if (is_object($this->value) && isset($this->value->$key)) {
            return new static($this->value->$key);
        }

        return new static($default);
    }

    /**
     * Отримати реальне значення
     */
    public function val(): mixed
    {
        return $this->value;
    }

    /**
     * Перевірити, чи значення дорівнює null
     */
    public function isNull(): bool
    {
        return $this->value === null;
    }

    /**
     * Перетворити на масив (рекурсивно)
     */
    public function toArray(): array
    {
        if (is_array($this->value)) {
            return $this->value;
        }

        if (is_object($this->value)) {
            return json_decode(json_encode($this->value), true) ?? [];
        }

        return [];
    }

    /**
     * Перетворити на об'єкт (рекурсивно)
     */
    public function toObject(): object
    {
        if (is_object($this->value)) {
            return $this->value;
        }

        if (is_array($this->value)) {
            return json_decode(json_encode($this->value), false) ?? (object)[];
        }

        return (object)[];
    }

    /**
     * Автоматичне приведення до рядка
     */
    public function __toString(): string
    {
        return is_scalar($this->value) ? (string)$this->value : '';
    }

    /**
     * Магія звернення до властивості як $obj->key
     */
    public function __get($name): static
    {
        return $this->get($name);
    }
}
