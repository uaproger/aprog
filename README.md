![aprog](/src/resources/assets/favicon.png)
# aprog

### 🇺🇦 Kyiv / Ukraine

**aprog** — допоміжна бібліотека для Laravel, що об'єднує набір багаторазових компонентів, хелперів, сервісів та Artisan-команд для спрощення й прискорення розробки застосунків.

Бібліотека створювалася як набір інструментів для роботи з `Properties`, але з часом значно розширилася та перетворилася на універсальний набір допоміжних засобів для Laravel-проєктів.

Основна мета **aprog** — винести повторювану службову логіку в готові компоненти, які можна використовувати в різних проєктах без необхідності реалізовувати однаковий функціонал знову.

Серед можливостей бібліотеки:

- генерація `Properties`;
- генерація `Services`;
- генерація `Accumulators`;
- генерація `Enums`;
- безпечна робота з масивами та об'єктами;
- накопичення й обробка помилок;
- структуроване логування;
- робота з логами застосунку;
- надсилання повідомлень у Telegram;
- формування листів про помилки для розробників;
- генерація GUID;
- вимірювання часу виконання коду;
- моніторинг використання пам'яті;
- допоміжні функції для типових задач Laravel-проєктів.

---

## Packagist

```shell
https://packagist.org/packages/uaproger/aprog
```

---

# Installation

**aprog** requires PHP >= 8.1.

Install the package via Composer:

```shell
composer require uaproger/aprog
```

---

# Basic Usage

## Property

Створення нового класу `Property`:

```shell
php artisan make:property <name>
```

Приклад:

```shell
php artisan make:property User
```

---

## Service

Створення нового сервісу:

```shell
php artisan make:service <name>
```

Приклад:

```shell
php artisan make:service Payment
```

---

## Accumulator

Створення нового `Accumulator`.

### Default version

```shell
php artisan make:accumulator <name>
```

### Laravel version

Laravel-версія потребує реєстрації в Service Provider:

```shell
php artisan make:accumulator <name> --l
```

---

## Enum

Створення нового `Enum`.

### String Enum

Використовується за замовчуванням:

```shell
php artisan make:enum <name>
```

або:

```shell
php artisan make:enum <name> --s
```

### Integer Enum

```shell
php artisan make:enum <name> --i
```

---

# Configuration

## Lang translations

Для використання конфігурації `Lang::translations()` опублікуйте конфіг бібліотеки:

```shell
php artisan vendor:publish --provider="Aprog\AprogServiceProvider" --tag=config
```

---

## MailForDeveloper View

Для публікації Blade View, що використовується `MailForDeveloper`:

```shell
php artisan vendor:publish --provider="Aprog\AprogServiceProvider" --tag=views
```

---

# Favicon

Бібліотека містить власний `favicon.png`, який може використовуватися безпосередньо з пакета або бути опублікований у Laravel-проєкт.

Для публікації favicon:

```shell
php artisan vendor:publish --tag=aprog-favicon
```

Якщо favicon уже існує та його потрібно перезаписати:

```shell
php artisan vendor:publish --tag=aprog-favicon --force
```

Після публікації favicon можна використовувати у Blade:

```blade
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
```

# Font

**aprog** використовує шрифт [Poppins](https://fonts.google.com/specimen/Poppins) як основний шрифт бренду.

**Poppins** — геометричний sans-serif шрифт із сімейства Google Fonts, розроблений Indian Type Foundry.

Для публікації шрифта:
```shell
php artisan vendor:publish --tag=aprog-assets
```
Для використання в Laravel-проєкті шрифт може бути підключений через Google Fonts:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Aprog:wght@300;400;500&display=swap" rel="stylesheet">
```
Після підключення:
```css
body {
    font-family: 'Aprog', sans-serif;
}
```
Для логотипу та елементів бренду aprog рекомендована вага:
```css
.aprog-logo {
    font-family: 'Aprog', sans-serif;
    font-weight: 300;
}
```

---

# Helpers

**aprog** містить набір глобальних helper-функцій для типових задач Laravel-проєктів.

### `code_location()`

Дозволяє отримати файл та номер рядка поточного місця виконання коду.

### `arr()`

Безпечне отримання значень із масиву або об'єкта.

### `object()`

Формує об'єкт із масиву або створює порожній об'єкт.

### `mail_for_developer()`

Дозволяє сформувати та відправити `MailForDeveloper`.

### `mail_content_exception()`

Формує контент для тіла листа з інформацією про exception.

### `guid()`

Генерує новий GUID.

При передачі даних дозволяє отримувати детермінований GUID для однакового набору вхідних даних.

### `blockLogError()`

Допоміжна функція для структурованого логування помилок.

### `blockInfo()`

Формує структурований інформаційний блок у логах.

### `blockExceptionError()`

Формує структурований блок логування exception.

### `zerosArticle()`

Допоміжна функція для роботи з артикулами.

### `exception()`

Викликає клас `SetAprog` та його метод `exception`, який своєю чергою викликає переданий клас і метод.

### `telegram()`

Дозволяє надсилати повідомлення у Telegram-групи.

### `checkMemory()`

Дозволяє отримати інформацію про поточне та пікове використання пам'яті під час виконання запиту.

### `bugger()`

Дозволяє накопичувати логові повідомлення та виводити їх через `blockInfo()` однією структурованою групою.

### `route_logs()`

Отримання списку всіх логів.

### `route_log()`

Отримання конкретного лога.

### `route_remove_log()`

Видалення конкретного лога.

### `isPhone()`

Проста перевірка коректності номера телефону.

### `uniqueBrowser()`

Дозволяє отримати унікальний ID браузера користувача.

### `ip()`

Отримання IP-адреси користувача.

### `timeStart()`

Запускає таймер для вимірювання часу виконання коду.

### `timeStop()`

Зупиняє таймер та повертає час виконання у форматі:

```text
00:00:00.000
```

---

# Components

## ArrWrapper

Бібліотека містить клас `ArrWrapper` та helper-функцію:

```php
wrap()
```

Вони призначені для безпечної роботи з вкладеними структурами даних та дозволяють зручніше отримувати значення з масивів і об'єктів.

---

## Accumulated Errors

**aprog** містить сервіс накопичення помилок, який дозволяє збирати помилки під час виконання операції та централізовано працювати з ними.

---

## MailForDeveloper

Клас `MailForDeveloper` призначений для формування листів з інформацією про помилки для розробників.

Разом із helper-функціями:

```php
mail_for_developer()
mail_content_exception()
```

він спрощує формування повідомлень про exception та інші помилки застосунку.

---

## ErrorCodes

Бібліотека містить:

- модель `ErrorCodes`;
- migration;
- seeder.

Це дозволяє централізовано працювати з кодами помилок у Laravel-застосунках.

---

# Philosophy

**aprog** створюється як набір практичних інструментів, які виникають із реальних задач Laravel-проєктів.

Якщо певний код або підхід доводиться повторювати в різних проєктах — він може стати частиною **aprog**.

Бібліотека продовжує розвиватися та поповнюватися новими компонентами, сервісами, хелперами й інструментами.

---

# English

**aprog** is a utility library for Laravel that provides a collection of reusable components, helpers, services, and Artisan commands designed to simplify and speed up application development.

The library originally started as a set of tools for creating and working with `Properties`, but over time it has evolved into a broader toolkit for Laravel projects.

The main goal of **aprog** is to move repetitive application and infrastructure logic into reusable components that can be shared across multiple projects.

### Key Features

- `Properties`, `Services`, `Accumulators`, and `Enums` generators;
- safe access to nested arrays and objects;
- error accumulation and centralized error handling;
- structured application logging;
- log management utilities;
- Telegram notifications;
- developer error reporting with `MailForDeveloper`;
- random and deterministic GUID generation;
- execution-time measurement;
- memory usage monitoring;
- reusable helper functions for common Laravel development tasks;
- additional Artisan commands and development utilities.

> Built for Laravel projects where the same utility code should not have to be written twice.

---

# 🇺🇦

> Козацькому роду, нема переводу!  
> Слава Україні 🇺🇦

---

# License

MIT License

Copyright (c) 2026 AlexProger

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*&copy; AlexProger 2026*