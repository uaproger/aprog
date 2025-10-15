<?php

namespace Aprog\Services;

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
        $this->botToken = $token ?? env('TELEGRAM_BOT_TOKEN');

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
        int|string $chatId,
        string $text,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): array {
        return $this->request('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
        ]);
    }

    /**
     * Надіслати зображення (фото)
     */
    public function photo(
        int|string $chatId,
        string $photoUrlOrFileId,
        ?string $caption = null,
        ?string $parseMode = null
    ): array {
        return $this->request('sendPhoto', [
            'chat_id' => $chatId,
            'photo' => $photoUrlOrFileId,
            'caption' => $caption,
            'parse_mode' => $parseMode,
        ]);
    }

    /**
     * Надіслати файл (документ)
     */
    public function document(
        int|string $chatId,
        string $documentUrlOrFileId,
        ?string $caption = null,
        ?string $parseMode = null
    ): array {
        return $this->request('sendDocument', [
            'chat_id' => $chatId,
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
}
