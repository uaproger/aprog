# aprog

Допоміжна бібліотека `Laravel` для створення Properties.

*require for dev*

### Packagist resource

```shell
https://packagist.org/packages/uaproger/aprog
```

### Installation

Aprog requires PHP >= 8.1.

```shell
composer require uaproger/aprog --dev
```

### Basic Usage

Property:
```shell
php artisan make:property <name>
```

Service:
```shell
php artisan make:service <name>
```

Config for Lang::translations():
```shell
php artisan vendor:publish --provider="Aprog\AprogServiceProvider" --tag=config
```

View for MailForDeveloper:
```shell
php artisan vendor:publish --provider="Aprog\AprogServiceProvider" --tag=views
```


### Info

- На цей час сервіс вже створює properties та services
- Додано Сервіс накопичення помилок
- Додано чотири функції хелперів:
    - `code_location()` для отримання файлу та лінії
    - `arr()` для безпечного отримання значень з масиву, або об'єкта
    - `object()` формування об'єкта з масиву, або створення порожнього об'єкта
    - `mail_for_developer()` дозволяє формувати `MailForDeveloper` лист
    - `mail_content_exception()` формування контенту для тіла листа
- Додано клас `MailForDeveloper` - формує лист з помилкою для розробника
- З часом будуть додаватися можливості створення додаткових інструментів

### License
MIT License

Copyright (c) 2025 AlexProger

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

*&copy; AlexProger 2025*