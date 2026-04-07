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
     * wrap($data)->path('user.profile.name');
     * ```
     *
     * @param string $path Шлях із крапками
     * @param mixed $default Значення за замовчуванням
     * @return mixed
     */
    public function path(string $path, mixed $default = null): mixed
    {
        $segments = explode('.', $path);
        $current = $this;

        foreach ($segments as $key) {
            $current = $current->get($key);
            if ($current->isNull()) {
                return $default;
            }
        }

        return $current->value;
    }

    /**
     * Отримати реальне значення без обгортки.
     *
     *  Використання:
     *  ```php
     *  wrap($data)->val('user.profile.name');
     *  ```
     *
     * v2
     * ```php
     *  wrap($data)->get('user')->get('profile')->val('name');
     * ```
     *
     * v3
     * ```php
     *  wrap($data)->getValue('user');
     * ```
     *
     * v4
     * ```php
     *  wrap($data)->pathValue('user.profile.name');
     * ```
     *
     * @param string|int|null $key
     * @param mixed $default
     * @return mixed
     */
    public function val(string|int|null $key = null, mixed $default = null): mixed
    {
        if (!is_null($key)) {
            $segments = explode('.', $key);
            if (count($segments) > 1) {
                return $this->path($key, $default);
            }
            return $this->get($key, $default)->value;
        }
        return $this->value;
    }
    public function getValue(string|int $key, mixed $default = null): mixed
    {
        return $this->get($key, $default)->value;
    }
    public function pathValue(string $path, mixed $default = null): mixed
    {
        return $this->path($path, $default);
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
     * Перевірити, чи значення порожнє.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
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
     * Виконати callback-поєднання над кожним елементом.
     *
     * @param callable $callback
     * @param mixed|null $initial
     * @return mixed
     */
    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $acc = $initial;

        foreach ($this->toArray() as $key => $value) {
            $acc = $callback($acc, $value, $key);
        }

        return $acc;
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
     * Отримати зріз елементів (як array_slice або Collection::slice).
     *
     * @param int $offset Індекс з якого починати
     * @param int|null $length Кількість елементів (null — до кінця)
     * @return static
     */
    public function slice(int $offset, ?int $length = null): static
    {
        $array = $this->toArray();

        if ($length === null) {
            return new static(array_slice($array, $offset));
        }

        return new static(array_slice($array, $offset, $length));
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
     * Перевірка існування значення по шляху.
     *
     * @param string $keyOrPath
     * @return bool
     */
    public function exists(string $keyOrPath): bool
    {
        $segments = explode('.', $keyOrPath);
        if (count($segments) > 1) return !$this->path($keyOrPath)->isNull();
        return !$this->get($keyOrPath)->isNull();
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
     *  Використання:
     *  ```php
     *  wrap($data)->key;
     *  ```
     *
     * @param string $name
     * @return static
     */
    public function __get(string $name): static
    {
        return $this->get($name);
    }

    /**
     * Магічна перевірка isset($wrap->key)
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }
}
