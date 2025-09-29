<?php

namespace Aprog\Services;

use Illuminate\Support\Collection;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * ArrWrapper Service
 *
 * --------------------------------------------------------------------------
 *  Сервіс ArrWrapper Дозволяє безпечно працювати з вкладеними даними
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
class ArrWrapper
{
    protected mixed $value;

    /**
     * Створює новий обгортувач значення
     *
     * @param mixed $value Будь-яке значення: масив, об'єкт, Collection або скаляр
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Перевірити, чи існує ключ у внутрішньому значенні.
     *
     * Використання:
     * ```php
     * wrap($data)->has('user');
     * ```
     *
     * @param string|int $key
     * @return bool
     */
    public function has(string|int $key): bool
    {
        if ($this->value instanceof Collection) {
            return array_key_exists($key, $this->value->toArray());
        }

        if (is_array($this->value)) {
            return array_key_exists($key, $this->value);
        }

        if (is_object($this->value)) {
            return isset($this->value->$key);
        }

        return false;
    }

    /**
     * Отримати вкладене значення по ключу (масив / об'єкт / Collection).
     *
     * Використання:
     * ```php
     * wrap($data)->get('key')->val();
     * ```
     *
     * @param string|int $key Ключ або індекс
     * @param mixed $default Значення за замовчуванням, якщо ключ не знайдено
     * @return static Новий ArrWrapper із отриманим значенням
     */
    public function get(string|int $key, mixed $default = null): static
    {
        if ($this->value instanceof Collection) {
            $array = $this->value->toArray();
            return new static($array[$key] ?? $default);
        }

        if (is_array($this->value) && array_key_exists($key, $this->value)) {
            return new static($this->value[$key]);
        }

        if (is_object($this->value) && isset($this->value->$key)) {
            return new static($this->value->$key);
        }

        return new static($default);
    }

    /**
     * Отримати значення за шляхом у форматі 'a.b.c'.
     *
     * Використання:
     * ```php
     * wrap($data)->path('user.profile.name')->val();
     * ```
     *
     * @param string $path Шлях із крапками
     * @param mixed $default Значення за замовчуванням
     * @return static
     */
    public function path(string $path, mixed $default = null): static
    {
        $segments = explode('.', $path);
        $current = $this;

        foreach ($segments as $key) {
            $current = $current->get($key);
            if ($current->isNull()) {
                return new static($default);
            }
        }

        return $current;
    }

    /**
     * Отримати реальне значення без обгортки.
     *
     * @return mixed
     */
    public function val(): mixed
    {
        return $this->value;
    }

    /**
     * Перевірити, чи значення дорівнює null.
     *
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->value === null;
    }

    /**
     * Перетворити внутрішнє значення на масив.
     *
     * Завжди повертає масив навіть якщо значення null.
     *
     * @return array
     */
    public function toArray(): array
    {
        if (is_array($this->value)) return $this->value;
        if ($this->value instanceof Collection) return $this->value->toArray();
        if (is_object($this->value)) return json_decode(json_encode($this->value), true) ?? [];
        return [];
    }

    /**
     * Перетворити внутрішнє значення на об'єкт (stdClass).
     *
     * Завжди повертає об'єкт навіть якщо значення null.
     *
     * @return object
     */
    public function toObject(): object
    {
        if (is_object($this->value)) return $this->value;
        if ($this->value instanceof Collection) return (object)$this->value->toArray();
        if (is_array($this->value)) return json_decode(json_encode($this->value), false) ?? (object)[];
        return (object)[];
    }

    /**
     * Перетворити внутрішнє значення на колекцію Laravel.
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

    /**
     * Перетворити внутрішнє значення у JSON‑рядок.
     *
     * @param int $flags Флаги для json_encode()
     * @return string
     */
    public function toJson(int $flags = 0): string
    {
        return json_encode($this->toArray(), $flags);
    }

    /**
     * Виконати callback над кожним елементом (як Collection::map()).
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): static
    {
        return new static(
            collect($this->toArray())->map($callback)->all()
        );
    }

    /**
     * Відфільтрувати елементи (як Collection::filter()).
     *
     * @param callable|null $callback
     * @return static
     */
    public function filter(callable $callback = null): static
    {
        return new static(
            collect($this->toArray())->filter($callback)->all()
        );
    }

    /**
     * Дістати певний ключ із кожного елемента (як Collection::pluck()).
     *
     * @param string $key
     * @return static
     */
    public function pluck(string $key): static
    {
        return new static(
            collect($this->toArray())->pluck($key)->all()
        );
    }

    /**
     * Відсортувати елементи (як Collection::sort()).
     *
     * @param callable|null $callback
     * @return static
     */
    public function sort(callable $callback = null): static
    {
        return new static(
            collect($this->toArray())->sort($callback)->values()->all()
        );
    }

    /**
     * Встановити значення за ключем (оновлює масив).
     *
     * Використання:
     * ```php
     * wrap($data)->set('newKey', 'value')->val();
     * ```
     *
     * @param string|int|null $key
     * @param mixed|null $value
     * @return static
     */
    public function set(string|int|null $key, mixed $value = null): static
    {
        $data = $this->toArray();

        if ($value === null) {
            $value = $key;
            $key = null;
        }

        if ($key === null) {
            # Додати в кінець масиву
            $data[] = $value;
            return new static($data);
        }

        # Якщо ключ має шлях "a.b.c"
        if (str_contains($key, '.')) {
            $segments = explode('.', $key);
            $ref = &$data;

            foreach ($segments as $segment) {
                if (!is_array($ref)) {
                    $ref = []; # Створити масив, якщо раніше було щось інше
                }

                if (!array_key_exists($segment, $ref)) {
                    $ref[$segment] = [];
                }

                $ref = &$ref[$segment];
            }

            $ref = $value;
            return new static($data);
        }

        # Просте встановлення
        $data[$key] = $value;
        return new static($data);
    }

    /**
     * Оновити значення або кілька значень у структурі
     *
     * @param string|array $keyOrArray — або ключ зі шляхом (наприклад, 'user.name'), або масив ключів => значень
     * @param mixed|null $value — нове значення (використовується тільки, якщо $keyOrArray — це рядок)
     * @return static
     */
    public function update(string|array $keyOrArray, mixed $value = null): static
    {
        $data = $this->toArray();

        # Масове оновлення
        if (is_array($keyOrArray)) {
            foreach ($keyOrArray as $key => $val) {
                $data = (new static($data))->set($key, $val)->toArray();
            }
            return new static($data);
        }

        # Один ключ
        return $this->set($keyOrArray, $value);
    }

    /**
     * Приведення до рядка при echo/print.
     *
     * @return string
     */
    public function __toString(): string
    {
        return is_scalar($this->value) ? (string)$this->value : '';
    }

    /**
     * Магічний доступ до властивостей об'єкта як $wrap->key.
     *
     * @param string $name
     * @return static
     */
    public function __get(string $name): static
    {
        return $this->get($name);
    }
}
