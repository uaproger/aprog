<?php

namespace Aprog\Services;

use Aprog\Exceptions\AprogException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * TelegramService — Повноцінний сервіс для Telegram Bot API для надсилання повідомлень (text)
 *
 * Copyright (c) 2025 AlexProger
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
            throw new RuntimeException("Telegram token не задано!");
        }
    }

    /**
     * Базовий запит до Telegram API
     */
    protected function request(string $method, array $params): array
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/{$method}";
        $attempt = 0;

        # 🧹 Видаляємо параметри з null-значеннями
        $params = array_filter($params, fn($v) => !is_null($v));

        while ($attempt < $this->maxRetries) {
            try {
                # Основний спосіб — через Laravel HTTP client
                $response = Http::timeout(10)->post($url, $params);

                if ($response->successful()) {
                    $data = $response->json();

                    if (is_array($data)) {
                        return $data;
                    }

                    blockLogError("Telegram API повернув некоректний JSON: " . $response->body());
                } else {
                    blockLogError("Telegram API error: {$response->status()} — {$response->body()}");
                }
            } catch (Throwable $e) {
                blockLogError("TelegramService (Laravel HTTP) exception: " . $e->getMessage());

                # fallback: file_get_contents (GET-запит)
                try {
                    $query = http_build_query($params);
                    $fallbackUrl = $url . '?' . $query;

                    $raw = file_get_contents($fallbackUrl);
                    $data = json_decode($raw, true);

                    if (is_array($data)) {
                        return $data;
                    }

                    blockLogError("Telegram fallback JSON decode error: " . $raw);
                } catch (Throwable $e2) {
                    blockLogError("TelegramService fallback exception: " . $e2->getMessage());
                }
            }

            usleep($this->retryDelay * 1000);
            $attempt++;
        }

        throw new RuntimeException("Не вдалося звернутись до Telegram API після {$this->maxRetries} спроб");
    }

    /**
     * Відправити текстове повідомлення
     * @throws AprogException
     */
    public function message(
        string $text,
        int|string|null $chatId = null,
        ?string $parseMode = null,
        bool $disableWebPagePreview = false
    ): array {
        try {
            return $this->request('sendMessage', [
                'chat_id' => $this->resolveChatId($chatId),
                'text' => $text,
                'parse_mode' => $this->sanitizeParseMode($parseMode),
                'disable_web_page_preview' => $disableWebPagePreview,
            ]);
        } catch (Throwable $e) {
            throw new AprogException($e->getMessage());
        }
    }

    protected function sanitizeParseMode(?string $parseMode): ?string
    {
        $allowed = ['Markdown', 'MarkdownV2', 'HTML'];

        if (!$parseMode) return null;

        if (in_array($parseMode, $allowed, true)) {
            return $parseMode;
        }

        blockLogError("Непідтримуваний parse_mode: {$parseMode}. Буде скасовано.");
        return null;
    }

    protected function resolveChatId(int|string|null $chatId): int|string
    {
        $finalId = $chatId ?? config('telegram.user_id');

        if (!$finalId) {
            blockLogError("Не вказано chat_id для Telegram");
            throw new RuntimeException("Не вказано chat_id для Telegram");
        }

        return $finalId;
    }
}
