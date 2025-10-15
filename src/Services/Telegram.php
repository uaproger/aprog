<?php

namespace Aprog\Services;

use Aprog\Exceptions\AprogException;
use Aprog\Properties\Items\TelegramDataProperty;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * TelegramService — Повноцінний сервіс для Telegram Bot API
 *
 * Підтримка:
 * - Надсилання повідомлень (text)
 * - Надсилання зображень (photo)
 * - Надсилання файлів (document)
 * - Webhook-інтеграція
 * - Retry/reconnect логіка
 * - Кілька ботів (через параметр токена або env)
 *
 * @author
 * Copyright (c) {{ date('Y') }} AlexProger
 */
class Telegram
{
    protected string $botToken;
    protected int $maxRetries = 3;
    protected int $retryDelay = 200; # ms

    public function __construct(?string $token = null)
    {
        $this->botToken = $token ?? config('telegram.token');

        if (!$this->botToken) {
            throw new RuntimeException("Telegram token не задано");
        }
    }

    /**
     * Базовий запит до Telegram API
     */
    protected function request(string $method, array $params): array
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/{$method}";
        $attempt = 0;

        while ($attempt < $this->maxRetries) {
            try {
                $response = Http::timeout(10)->post($url, $params);

                if ($response->successful()) {
                    return $response->json();
                }

                blockLogError("Telegram API error: {$response->status()} - {$response->body()}");
            } catch (Throwable $e) {
                blockLogError("TelegramService error: " . $e->getMessage());
            }

            usleep($this->retryDelay * 1000); # затримка перед повтором
            $attempt++;
        }

        throw new RuntimeException("Не вдалося звернутись до Telegram API після {$this->maxRetries} спроб");
    }

    /**
     * Відправити текстове повідомлення
     */
    public function message(
        string $text,
        int|string|null $chatId = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): array {
        return $this->request('sendMessage', [
            'chat_id' => $chatId || config('telegram.user_id'),
            'text' => $text,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
        ]);
    }

    /**
     * Надіслати зображення (фото)
     */
    public function photo(
        string $photoUrlOrFileId,
        int|string|null $chatId = null,
        ?string $caption = null,
        ?string $parseMode = null
    ): array {
        return $this->request('sendPhoto', [
            'chat_id' => $chatId || config('telegram.user_id'),
            'photo' => $photoUrlOrFileId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
        ]);
    }

    /**
     * Надіслати файл (документ)
     */
    public function document(
        string $documentUrlOrFileId,
        int|string|null $chatId = null,
        ?string $caption = null,
        ?string $parseMode = null
    ): array {
        return $this->request('sendDocument', [
            'chat_id' => $chatId || config('telegram.user_id'),
            'document' => $documentUrlOrFileId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
        ]);
    }

    /**
     * Встановити Webhook (URL)
     */
    public function webhook(string $webhookUrl): array
    {
        return $this->request('setWebhook', [
            'url' => $webhookUrl
        ]);
    }

    /**
     * Видалити Webhook
     */
    public function deleteWebhook(): array
    {
        return $this->request('deleteWebhook', []);
    }

    /**
     * Отримати інформацію про Webhook
     */
    public function getWebhookInfo(): array
    {
        return $this->request('getWebhookInfo', []);
    }

    /**
     * Змінити токен у рантаймі (для кількох ботів)
     */
    public function withToken(string $newToken): static
    {
        $instance = clone $this;
        $instance->botToken = $newToken;
        return $instance;
    }

    /**
     * Отримати інформацію про чат (user, group, channel)
     *
     * @param string|int $chatIdOrUsername Наприклад: @al___er або числовий ID
     * @return TelegramDataProperty Інформація про чат, зокрема 'id'
     *
     * @throws RuntimeException Якщо Telegram API повернув помилку
     * @throws AprogException
     */
    public function info(string|int $chatIdOrUsername): TelegramDataProperty
    {
        return new TelegramDataProperty($this->request('getChat', [
            'chat_id' => $chatIdOrUsername,
        ]));
    }
}
